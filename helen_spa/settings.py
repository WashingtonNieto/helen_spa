"""
Configuración de Django para el proyecto Helen Spa.

Por defecto usa SQLite para que el proyecto funcione de inmediato en desarrollo.
Para producción con MySQL, define la variable de entorno DJANGO_USE_MYSQL=True
y configura las variables MYSQL_* (ver README.md).
"""
import os
from pathlib import Path

from dotenv import load_dotenv

BASE_DIR = Path(__file__).resolve().parent.parent

# Carga las variables definidas en el archivo .env (si existe) al entorno,
# para no tener que configurar variables de entorno manualmente en Windows.
load_dotenv(BASE_DIR / '.env')

SECRET_KEY = os.environ.get(
    'DJANGO_SECRET_KEY',
    'django-insecure-cambia-esta-clave-antes-de-producción-helen-spa'
)

DEBUG = os.environ.get('DJANGO_DEBUG', 'True') == 'True'

ALLOWED_HOSTS = os.environ.get('DJANGO_ALLOWED_HOSTS', '*').split(',')

INSTALLED_APPS = [
    'django.contrib.admin',
    'django.contrib.auth',
    'django.contrib.contenttypes',
    'django.contrib.sessions',
    'django.contrib.messages',
    'django.contrib.staticfiles',
    'spa',
]

MIDDLEWARE = [
    'django.middleware.security.SecurityMiddleware',
    'django.contrib.sessions.middleware.SessionMiddleware',
    'django.middleware.common.CommonMiddleware',
    'django.middleware.csrf.CsrfViewMiddleware',
    'django.contrib.auth.middleware.AuthenticationMiddleware',
    'django.contrib.messages.middleware.MessageMiddleware',
    'django.middleware.clickjacking.XFrameOptionsMiddleware',
]

ROOT_URLCONF = 'helen_spa.urls'

TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [BASE_DIR / 'templates'],
        'APP_DIRS': True,
        'OPTIONS': {
            'context_processors': [
                'django.template.context_processors.debug',
                'django.template.context_processors.request',
                'django.contrib.auth.context_processors.auth',
                'django.contrib.messages.context_processors.messages',
                'spa.context_processors.negocio',
            ],
        },
    },
]

WSGI_APPLICATION = 'helen_spa.wsgi.application'

USE_MYSQL = os.environ.get('DJANGO_USE_MYSQL', 'False') == 'True'

if USE_MYSQL:
    DATABASES = {
        'default': {
            'ENGINE': 'django.db.backends.mysql',
            'NAME': os.environ.get('MYSQL_DATABASE', 'helen_spa'),
            'USER': os.environ.get('MYSQL_USER', 'helen_spa_user'),
            'PASSWORD': os.environ.get('MYSQL_PASSWORD', ''),
            'HOST': os.environ.get('MYSQL_HOST', '127.0.0.1'),
            'PORT': os.environ.get('MYSQL_PORT', '3306'),
            'OPTIONS': {
                'charset': 'utf8mb4',
            },
        }
    }
else:
    DATABASES = {
        'default': {
            'ENGINE': 'django.db.backends.sqlite3',
            'NAME': BASE_DIR / 'db.sqlite3',
        }
    }

AUTH_PASSWORD_VALIDATORS = [
    {'NAME': 'django.contrib.auth.password_validation.UserAttributeSimilarityValidator'},
    {'NAME': 'django.contrib.auth.password_validation.MinimumLengthValidator'},
    {'NAME': 'django.contrib.auth.password_validation.CommonPasswordValidator'},
    {'NAME': 'django.contrib.auth.password_validation.NumericPasswordValidator'},
]

LANGUAGE_CODE = 'es-co'
TIME_ZONE = 'America/Bogota'
USE_I18N = True
USE_TZ = True

STATIC_URL = 'static/'
STATICFILES_DIRS = [BASE_DIR / 'spa' / 'static']
STATIC_ROOT = BASE_DIR / 'staticfiles'

MEDIA_URL = 'media/'
MEDIA_ROOT = BASE_DIR / 'media'

DEFAULT_AUTO_FIELD = 'django.db.models.BigAutoField'

# Panel interno (login de Helen para gestionar citas)
LOGIN_URL = 'spa:panel_login'
LOGIN_REDIRECT_URL = 'spa:panel_citas'
LOGOUT_REDIRECT_URL = 'spa:inicio'

# ---------------------------------------------------------------------------
# Correo (para notificar al cliente cuando su cita se confirma)
# ---------------------------------------------------------------------------
# Mientras no configures un servidor SMTP real, los correos se imprimen en la
# consola donde corre "runserver" (no se envían de verdad). Es útil para
# probar sin necesidad de credenciales todavía.
#
# Para enviar correos de verdad, define estas variables de entorno, por
# ejemplo usando tu correo de Hostinger o Gmail (con contraseña de aplicación):
#   DJANGO_EMAIL_BACKEND=django.core.mail.backends.smtp.EmailBackend
#   EMAIL_HOST=smtp.hostinger.com          (o smtp.gmail.com)
#   EMAIL_PORT=465
#   EMAIL_USE_TLS=False
#   EMAIL_USE_SSL=True
#   EMAIL_HOST_USER=notificaciones@tudominio.com
#   EMAIL_HOST_PASSWORD=tu-contraseña
#   DEFAULT_FROM_EMAIL=Helen Spa <notificaciones@tudominio.com>
EMAIL_BACKEND = os.environ.get(
    'DJANGO_EMAIL_BACKEND', 'django.core.mail.backends.console.EmailBackend'
)
EMAIL_HOST = os.environ.get('EMAIL_HOST', 'smtp.gmail.com')
EMAIL_PORT = int(os.environ.get('EMAIL_PORT', 587))
EMAIL_USE_TLS = os.environ.get('EMAIL_USE_TLS', 'True') == 'True'
EMAIL_USE_SSL = os.environ.get('EMAIL_USE_SSL', 'False') == 'True'
EMAIL_HOST_USER = os.environ.get('EMAIL_HOST_USER', '')
EMAIL_HOST_PASSWORD = os.environ.get('EMAIL_HOST_PASSWORD', '')
DEFAULT_FROM_EMAIL = os.environ.get('DEFAULT_FROM_EMAIL', 'Helen Spa <notificaciones@helenspa.com>')

NEGOCIO = {
    'nombre': 'Helen Spa',
    'eslogan': 'Tu bienestar, nuestra prioridad',
    'telefono': '+57 300 518 0354',
    'whatsapp': '573005180354',
    'email': 'cordobatamayohelen@gmail.com',
    'direccion': 'Soacha, Colombia',
    'instagram': 'https://instagram.com/helenspa',
    'facebook': 'https://facebook.com/helenspa',
}
