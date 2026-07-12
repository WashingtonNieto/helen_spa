from datetime import time

from django.core.exceptions import ValidationError
from django.db import models
from django.utils import timezone
from django.utils.text import slugify

# Horario de atención: día de la semana (0=Lunes ... 6=Domingo) -> (apertura, cierre)
# Lunes a viernes: 10:00 am - 4:00 pm. Sábados: 10:00 am - 2:00 pm. Domingo: cerrado.
HORARIO_ATENCION = {
    0: (time(10, 0), time(16, 0)),
    1: (time(10, 0), time(16, 0)),
    2: (time(10, 0), time(16, 0)),
    3: (time(10, 0), time(16, 0)),
    4: (time(10, 0), time(16, 0)),
    5: (time(10, 0), time(14, 0)),
}

DIAS_SEMANA = {
    0: 'lunes', 1: 'martes', 2: 'miércoles', 3: 'jueves',
    4: 'viernes', 5: 'sábado', 6: 'domingo',
}


class Servicio(models.Model):
    nombre = models.CharField(max_length=100)
    slug = models.SlugField(max_length=110, unique=True, blank=True)
    descripcion_corta = models.CharField(
        max_length=200, blank=True,
        help_text="Frase breve mostrada en las tarjetas de servicio."
    )
    descripcion = models.TextField()
    duracion_minutos = models.PositiveIntegerField(
        default=60, help_text="Duración aproximada en minutos."
    )
    precio = models.DecimalField(
        max_digits=10, decimal_places=2, null=True, blank=True,
        help_text="Déjalo vacío si el precio se informa a solicitud."
    )
    icono = models.CharField(
        max_length=10, blank=True, default="💆",
        help_text="Emoji o símbolo corto para representar el servicio."
    )
    imagen = models.ImageField(upload_to='servicios/', blank=True, null=True)
    orden = models.PositiveIntegerField(default=0)
    activo = models.BooleanField(default=True)

    class Meta:
        ordering = ['orden', 'nombre']
        verbose_name = "Servicio"
        verbose_name_plural = "Servicios"

    def __str__(self):
        return self.nombre

    def save(self, *args, **kwargs):
        if not self.slug:
            base_slug = slugify(self.nombre)
            slug = base_slug
            contador = 1
            while Servicio.objects.filter(slug=slug).exclude(pk=self.pk).exists():
                contador += 1
                slug = f"{base_slug}-{contador}"
            self.slug = slug
        super().save(*args, **kwargs)


class Cita(models.Model):
    ESTADO_PENDIENTE = 'pendiente'
    ESTADO_CONFIRMADA = 'confirmada'
    ESTADO_CANCELADA = 'cancelada'
    ESTADO_CHOICES = [
        (ESTADO_PENDIENTE, 'Pendiente'),
        (ESTADO_CONFIRMADA, 'Confirmada'),
        (ESTADO_CANCELADA, 'Cancelada'),
    ]

    nombre_cliente = models.CharField("Nombre completo", max_length=150)
    telefono = models.CharField("Teléfono / WhatsApp", max_length=20)
    email = models.EmailField("Correo electrónico", blank=True)
    servicio = models.ForeignKey(
        Servicio, on_delete=models.PROTECT, related_name='citas', verbose_name="Servicio"
    )
    fecha = models.DateField("Fecha")
    hora = models.TimeField("Hora")
    notas = models.TextField("Notas adicionales", blank=True)
    estado = models.CharField(max_length=12, choices=ESTADO_CHOICES, default=ESTADO_PENDIENTE)
    creado = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ['fecha', 'hora']
        verbose_name = "Cita"
        verbose_name_plural = "Citas"

    def __str__(self):
        return f"{self.nombre_cliente} - {self.servicio} ({self.fecha} {self.hora})"

    def clean(self):
        errores = {}

        if self.fecha and self.fecha < timezone.localdate():
            errores['fecha'] = "No se puede agendar una cita en una fecha pasada."

        if self.fecha is not None and 'fecha' not in errores:
            dia_semana = self.fecha.weekday()
            horario = HORARIO_ATENCION.get(dia_semana)
            if horario is None:
                errores['fecha'] = (
                    "No atendemos los domingos. Por favor elige de lunes a sábado."
                )
            elif self.hora is not None:
                apertura, cierre = horario
                if self.hora < apertura or self.hora >= cierre:
                    errores['hora'] = (
                        f"El {DIAS_SEMANA[dia_semana]} el horario de atención es de "
                        f"{apertura.strftime('%I:%M %p')} a {cierre.strftime('%I:%M %p')}."
                    )

        if self.fecha and self.hora and not errores:
            conflicto = Cita.objects.filter(
                fecha=self.fecha, hora=self.hora
            ).exclude(estado=self.ESTADO_CANCELADA)
            if self.pk:
                conflicto = conflicto.exclude(pk=self.pk)
            if conflicto.exists():
                errores['hora'] = "Ese horario ya está reservado. Por favor elige otra hora disponible."

        if errores:
            raise ValidationError(errores)


class GaleriaFoto(models.Model):
    titulo = models.CharField(max_length=150, blank=True)
    imagen = models.ImageField(upload_to='galeria/')
    descripcion = models.CharField(max_length=255, blank=True)
    orden = models.PositiveIntegerField(default=0)
    activo = models.BooleanField(default=True)

    class Meta:
        ordering = ['orden', '-id']
        verbose_name = "Foto de galería"
        verbose_name_plural = "Galería de fotos"

    def __str__(self):
        return self.titulo or f"Foto {self.pk}"
