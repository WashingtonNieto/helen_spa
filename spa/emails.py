"""Envío de correos relacionados con las citas."""
import logging

from django.conf import settings
from django.core.mail import send_mail
from django.utils.dateformat import format as django_date_format

logger = logging.getLogger(__name__)


def enviar_correo_confirmacion(cita):
    """
    Envía un correo al cliente avisando que su cita fue confirmada.
    Devuelve True si se envió (o se imprimió en consola), False si falló
    o si la cita no tiene correo registrado.
    """
    if not cita.email:
        return False

    negocio = getattr(settings, 'NEGOCIO', {})
    nombre_negocio = negocio.get('nombre', 'Helen Spa')
    telefono_negocio = negocio.get('telefono', '')
    direccion_negocio = negocio.get('direccion', '')

    fecha_formateada = django_date_format(cita.fecha, "l, d \\d\\e F \\d\\e Y")
    hora_formateada = django_date_format(cita.hora, "h:i A")

    asunto = f"Tu cita en {nombre_negocio} fue confirmada"
    mensaje = (
        f"Hola {cita.nombre_cliente},\n\n"
        f"Tu cita en {nombre_negocio} fue confirmada. Estos son los detalles:\n\n"
        f"Servicio: {cita.servicio.nombre}\n"
        f"Fecha: {fecha_formateada}\n"
        f"Hora: {hora_formateada}\n\n"
        f"Dirección: {direccion_negocio}\n"
        f"Cualquier duda o si necesitas reprogramar, contáctanos al {telefono_negocio}.\n\n"
        f"¡Te esperamos!\n"
        f"{nombre_negocio}"
    )

    try:
        send_mail(
            asunto,
            mensaje,
            settings.DEFAULT_FROM_EMAIL,
            [cita.email],
            fail_silently=False,
        )
        return True
    except Exception:
        logger.exception("No se pudo enviar el correo de confirmación para la cita %s", cita.pk)
        return False
