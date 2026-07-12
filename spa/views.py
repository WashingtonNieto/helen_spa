from urllib.parse import urlencode

from django.contrib import messages
from django.contrib.auth.decorators import login_required
from django.shortcuts import render, redirect, get_object_or_404
from django.urls import reverse
from django.views.decorators.http import require_POST

from .emails import enviar_correo_confirmacion
from .forms import CitaForm, BuscarCitaForm
from .models import Servicio, GaleriaFoto, Cita, HORARIO_ATENCION


def inicio(request):
    servicios_destacados = Servicio.objects.filter(activo=True)[:4]
    return render(request, 'spa/inicio.html', {
        'servicios_destacados': servicios_destacados,
    })


def servicios(request):
    lista_servicios = Servicio.objects.filter(activo=True)
    return render(request, 'spa/servicios.html', {
        'servicios': lista_servicios,
    })


def quien_soy(request):
    return render(request, 'spa/quien_soy.html')


def galeria(request):
    fotos = GaleriaFoto.objects.filter(activo=True)
    return render(request, 'spa/galeria.html', {
        'fotos': fotos,
    })


def agendamiento(request):
    if request.method == 'POST':
        form = CitaForm(request.POST)
        if form.is_valid():
            form.save()
            messages.success(
                request,
                "¡Tu cita fue registrada con éxito! Nos pondremos en contacto para confirmarla."
            )
            return redirect('spa:cita_confirmada')
    else:
        form = CitaForm()

    return render(request, 'spa/agendamiento.html', {
        'form': form,
        'horario': HORARIO_ATENCION,
    })


def cita_confirmada(request):
    return render(request, 'spa/cita_confirmada.html')


def mis_citas(request):
    """Consulta pública: un cliente ve sus propias citas buscando por teléfono."""
    buscado = 'telefono' in request.GET
    form = BuscarCitaForm(request.GET or None)
    citas = None

    if buscado and form.is_valid():
        telefono = form.cleaned_data['telefono'].strip()
        citas = Cita.objects.filter(telefono__icontains=telefono).select_related('servicio')

    return render(request, 'spa/mis_citas.html', {
        'form': form,
        'citas': citas,
        'buscado': buscado,
    })


@login_required(login_url='spa:panel_login')
def panel_citas(request):
    """Panel interno (requiere login) para que Helen vea y gestione todas las citas."""
    citas = Cita.objects.select_related('servicio').all()

    fecha_filtro = request.GET.get('fecha', '')
    estado_filtro = request.GET.get('estado', '')

    if fecha_filtro:
        citas = citas.filter(fecha=fecha_filtro)
    if estado_filtro:
        citas = citas.filter(estado=estado_filtro)

    return render(request, 'spa/panel_citas.html', {
        'citas': citas,
        'fecha_filtro': fecha_filtro,
        'estado_filtro': estado_filtro,
        'estados': Cita.ESTADO_CHOICES,
    })


@login_required(login_url='spa:panel_login')
@require_POST
def cambiar_estado_cita(request, pk):
    cita = get_object_or_404(Cita, pk=pk)
    nuevo_estado = request.POST.get('estado')
    estados_validos = dict(Cita.ESTADO_CHOICES)
    estado_anterior = cita.estado

    if nuevo_estado in estados_validos:
        cita.estado = nuevo_estado
        cita.save(update_fields=['estado'])
        messages.success(
            request,
            f"Cita de {cita.nombre_cliente} actualizada a '{estados_validos[nuevo_estado]}'."
        )

        # Avisar por correo al cliente solo cuando la cita pasa a "confirmada"
        if nuevo_estado == Cita.ESTADO_CONFIRMADA and estado_anterior != Cita.ESTADO_CONFIRMADA:
            if cita.email:
                if enviar_correo_confirmacion(cita):
                    messages.info(request, f"Se envió un correo de confirmación a {cita.email}.")
                else:
                    messages.warning(request, f"No se pudo enviar el correo a {cita.email}. Revisa la configuración de correo.")
            else:
                messages.warning(request, f"{cita.nombre_cliente} no dejó correo registrado, no se envió notificación.")

    params = {}
    if request.POST.get('fecha_filtro'):
        params['fecha'] = request.POST.get('fecha_filtro')
    if request.POST.get('estado_filtro'):
        params['estado'] = request.POST.get('estado_filtro')

    url = reverse('spa:panel_citas')
    if params:
        url += '?' + urlencode(params)
    return redirect(url)
