from django.contrib.auth import views as auth_views
from django.urls import path
from . import views

app_name = 'spa'

urlpatterns = [
    path('', views.inicio, name='inicio'),
    path('servicios/', views.servicios, name='servicios'),
    path('quien-soy/', views.quien_soy, name='quien_soy'),
    path('agendamiento/', views.agendamiento, name='agendamiento'),
    path('agendamiento/confirmada/', views.cita_confirmada, name='cita_confirmada'),
    path('galeria/', views.galeria, name='galeria'),
    path('mis-citas/', views.mis_citas, name='mis_citas'),

    # Panel interno de Helen (requiere iniciar sesión)
    path('panel/login/', auth_views.LoginView.as_view(template_name='spa/panel_login.html'), name='panel_login'),
    path('panel/logout/', auth_views.LogoutView.as_view(next_page='spa:inicio'), name='panel_logout'),
    path('panel/citas/', views.panel_citas, name='panel_citas'),
    path('panel/citas/<int:pk>/estado/', views.cambiar_estado_cita, name='cambiar_estado_cita'),
]
