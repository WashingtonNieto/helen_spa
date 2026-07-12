from django.conf import settings


def negocio(request):
    return {'negocio': getattr(settings, 'NEGOCIO', {})}
