from django.db import migrations, models
import django.db.models.deletion


class Migration(migrations.Migration):

    initial = True

    dependencies = []

    operations = [
        migrations.CreateModel(
            name='Servicio',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('nombre', models.CharField(max_length=100)),
                ('slug', models.SlugField(blank=True, max_length=110, unique=True)),
                ('descripcion_corta', models.CharField(blank=True, help_text='Frase breve mostrada en las tarjetas de servicio.', max_length=200)),
                ('descripcion', models.TextField()),
                ('duracion_minutos', models.PositiveIntegerField(default=60, help_text='Duración aproximada en minutos.')),
                ('precio', models.DecimalField(blank=True, decimal_places=2, help_text='Déjalo vacío si el precio se informa a solicitud.', max_digits=10, null=True)),
                ('icono', models.CharField(blank=True, default='💆', help_text='Emoji o símbolo corto para representar el servicio.', max_length=10)),
                ('imagen', models.ImageField(blank=True, null=True, upload_to='servicios/')),
                ('orden', models.PositiveIntegerField(default=0)),
                ('activo', models.BooleanField(default=True)),
            ],
            options={
                'verbose_name': 'Servicio',
                'verbose_name_plural': 'Servicios',
                'ordering': ['orden', 'nombre'],
            },
        ),
        migrations.CreateModel(
            name='GaleriaFoto',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('titulo', models.CharField(blank=True, max_length=150)),
                ('imagen', models.ImageField(upload_to='galeria/')),
                ('descripcion', models.CharField(blank=True, max_length=255)),
                ('orden', models.PositiveIntegerField(default=0)),
                ('activo', models.BooleanField(default=True)),
            ],
            options={
                'verbose_name': 'Foto de galería',
                'verbose_name_plural': 'Galería de fotos',
                'ordering': ['orden', '-id'],
            },
        ),
        migrations.CreateModel(
            name='Cita',
            fields=[
                ('id', models.BigAutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('nombre_cliente', models.CharField(max_length=150, verbose_name='Nombre completo')),
                ('telefono', models.CharField(max_length=20, verbose_name='Teléfono / WhatsApp')),
                ('email', models.EmailField(blank=True, max_length=254, verbose_name='Correo electrónico')),
                ('fecha', models.DateField(verbose_name='Fecha')),
                ('hora', models.TimeField(verbose_name='Hora')),
                ('notas', models.TextField(blank=True, verbose_name='Notas adicionales')),
                ('estado', models.CharField(choices=[('pendiente', 'Pendiente'), ('confirmada', 'Confirmada'), ('cancelada', 'Cancelada')], default='pendiente', max_length=12)),
                ('creado', models.DateTimeField(auto_now_add=True)),
                ('servicio', models.ForeignKey(on_delete=django.db.models.deletion.PROTECT, related_name='citas', to='spa.servicio', verbose_name='Servicio')),
            ],
            options={
                'verbose_name': 'Cita',
                'verbose_name_plural': 'Citas',
                'ordering': ['fecha', 'hora'],
            },
        ),
    ]
