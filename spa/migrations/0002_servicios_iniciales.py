from django.db import migrations
from django.utils.text import slugify


SERVICIOS = [
    {
        'nombre': 'Maderoterapia (reducción y moldeo)',
        'descripcion_corta': 'Técnica con instrumentos de madera para reducir medidas y moldear la figura.',
        'descripcion': (
            'Terapia manual asistida con instrumentos de madera que estimula la circulación, '
            'ayuda a reducir medidas, combatir la celulitis y moldear el contorno corporal.'
        ),
        'duracion_minutos': 60,
        'icono': '🪵',
        'orden': 1,
    },
    {
        'nombre': 'Masaje manual',
        'descripcion_corta': 'Masaje relajante y descontracturante realizado con las manos.',
        'descripcion': (
            'Masaje corporal realizado completamente a mano, enfocado en aliviar tensión muscular, '
            'mejorar la circulación y brindar relajación profunda.'
        ),
        'duracion_minutos': 60,
        'icono': '💆',
        'orden': 2,
    },
    {
        'nombre': 'Drenaje linfático',
        'descripcion_corta': 'Estimula el sistema linfático para reducir la retención de líquidos.',
        'descripcion': (
            'Técnica de masaje suave y rítmico que favorece la eliminación de toxinas y líquidos '
            'retenidos, disminuyendo la inflamación y mejorando la sensación de ligereza.'
        ),
        'duracion_minutos': 45,
        'icono': '🌊',
        'orden': 3,
    },
    {
        'nombre': 'Masaje con maderoterapia y bandas elásticas',
        'descripcion_corta': 'Combina maderoterapia con bandas elásticas para potenciar resultados.',
        'descripcion': (
            'Combinación de maderoterapia y bandas elásticas de resistencia que potencia la '
            'tonificación muscular y el moldeado corporal.'
        ),
        'duracion_minutos': 60,
        'icono': '🎗️',
        'orden': 4,
    },
    {
        'nombre': 'Geles térmicos (calor y frío)',
        'descripcion_corta': 'Terapia con geles de calor o frío según la necesidad del tratamiento.',
        'descripcion': (
            'Aplicación de geles térmicos de calor o frío como complemento de otros tratamientos, '
            'potenciando la reducción de medidas y aliviando molestias musculares.'
        ),
        'duracion_minutos': 30,
        'icono': '🔥',
        'orden': 5,
    },
    {
        'nombre': 'Yesoterapia',
        'descripcion_corta': 'Envolturas de yeso que ayudan a reafirmar y moldear el cuerpo.',
        'descripcion': (
            'Tratamiento con envolturas de yeso que ayuda a reafirmar la piel, reducir medidas '
            'y moldear zonas específicas del cuerpo.'
        ),
        'duracion_minutos': 45,
        'icono': '🩹',
        'orden': 6,
    },
    {
        'nombre': 'Criogénica',
        'descripcion_corta': 'Terapia de frío localizado para reducir inflamación y tonificar.',
        'descripcion': (
            'Terapia de frío controlado que ayuda a reducir la inflamación, tonificar la piel '
            'y potenciar los resultados de tratamientos corporales.'
        ),
        'duracion_minutos': 30,
        'icono': '❄️',
        'orden': 7,
    },
    {
        'nombre': 'Auriculoterapia',
        'descripcion_corta': 'Estimulación de puntos específicos del oído con fines terapéuticos.',
        'descripcion': (
            'Técnica basada en la estimulación de puntos reflejos en el oído para promover '
            'el equilibrio y bienestar general del cuerpo.'
        ),
        'duracion_minutos': 30,
        'icono': '👂',
        'orden': 8,
    },
]


def crear_servicios(apps, schema_editor):
    Servicio = apps.get_model('spa', 'Servicio')
    for datos in SERVICIOS:
        if not Servicio.objects.filter(nombre=datos['nombre']).exists():
            datos = dict(datos)
            datos['slug'] = slugify(datos['nombre'])
            Servicio.objects.create(**datos)


def eliminar_servicios(apps, schema_editor):
    Servicio = apps.get_model('spa', 'Servicio')
    nombres = [s['nombre'] for s in SERVICIOS]
    Servicio.objects.filter(nombre__in=nombres).delete()


class Migration(migrations.Migration):

    dependencies = [
        ('spa', '0001_initial'),
    ]

    operations = [
        migrations.RunPython(crear_servicios, eliminar_servicios),
    ]
