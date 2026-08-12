from datetime import date, time

from django import forms

from .models import Cita

# Genera franjas de 30 minutos entre 10:00 y 15:30.
# La validación real por día (lunes-viernes hasta 4pm, sábado hasta 2pm)
# se aplica en Cita.clean(); aquí solo ofrecemos las opciones posibles.
def _generar_horas():
    horas = []
    hh, mm = 10, 0
    while (hh, mm) < (16, 0):
        t = time(hh, mm)
        horas.append((t.strftime('%H:%M'), t.strftime('%I:%M %p')))
        mm += 30
        if mm == 60:
            mm = 0
            hh += 1
    return horas


HORA_CHOICES = _generar_horas()


class CitaForm(forms.ModelForm):
    hora = forms.ChoiceField(choices=HORA_CHOICES, label="Hora")
    fecha = forms.DateField(
        label="Fecha",
        widget=forms.DateInput(attrs={
            'type': 'date',
            'min': date.today().isoformat(),
        }),
    )

    class Meta:
        model = Cita
        fields = ['nombre_cliente', 'telefono', 'email', 'servicio', 'fecha', 'hora', 'notas']
        widgets = {
            'nombre_cliente': forms.TextInput(attrs={'placeholder': 'Nombre y apellido'}),
            'telefono': forms.TextInput(attrs={'placeholder': 'Ej: 3001234567'}),
            'email': forms.EmailInput(attrs={'placeholder': 'correo@ejemplo.com (opcional)'}),
            'notas': forms.Textarea(attrs={'rows': 3, 'placeholder': 'Alguna indicación especial (opcional)'}),
        }
        labels = {
            'nombre_cliente': 'Nombre completo',
            'telefono': 'Teléfono / WhatsApp',
        }

    def clean_hora(self):
        valor = self.cleaned_data['hora']
        hh, mm = map(int, valor.split(':'))
        return time(hh, mm)


class BuscarCitaForm(forms.Form):
    telefono = forms.CharField(
        label="Teléfono con el que agendaste",
        max_length=20,
        widget=forms.TextInput(attrs={'placeholder': 'Ej: 3001234567'}),
    )
