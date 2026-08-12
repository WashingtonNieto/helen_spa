from django.contrib import admin
from .models import Servicio, Cita, GaleriaFoto


@admin.register(Servicio)
class ServicioAdmin(admin.ModelAdmin):
    list_display = ('nombre', 'duracion_minutos', 'precio', 'orden', 'activo')
    list_editable = ('orden', 'activo')
    prepopulated_fields = {'slug': ('nombre',)}
    search_fields = ('nombre', 'descripcion')


@admin.register(Cita)
class CitaAdmin(admin.ModelAdmin):
    list_display = ('nombre_cliente', 'servicio', 'fecha', 'hora', 'estado', 'telefono', 'creado')
    list_filter = ('estado', 'servicio', 'fecha')
    list_editable = ('estado',)
    search_fields = ('nombre_cliente', 'telefono', 'email')
    date_hierarchy = 'fecha'
    ordering = ('fecha', 'hora')


@admin.register(GaleriaFoto)
class GaleriaFotoAdmin(admin.ModelAdmin):
    list_display = ('titulo', 'orden', 'activo')
    list_editable = ('orden', 'activo')
