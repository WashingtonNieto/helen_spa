# Helen Spa — Sitio web

Aplicación web para Helen Spa (masoterapia), construida con **Python + Django**.
Incluye: menú de navegación, página de servicios, "Quién soy", agendamiento de citas
con validación de horario, y galería de fotos administrable.

## Servicios incluidos por defecto

- Maderoterapia (reducción y moldeo)
- Masaje manual
- Drenaje linfático
- Masaje con maderoterapia y bandas elásticas
- Geles térmicos (calor y frío)
- Yesoterapia
- Criogénica
- Auriculoterapia

Se cargan automáticamente al ejecutar las migraciones (puedes editarlos, agregar
más o desactivarlos desde el panel de administración).

## Horario de agendamiento

- Lunes a viernes: 10:00 am – 4:00 pm
- Sábados: 10:00 am – 2:00 pm
- Domingos: cerrado

Estas reglas están validadas tanto en el formulario como en el modelo (`spa/models.py`,
diccionario `HORARIO_ATENCION`), y el sistema evita que dos clientes reserven la
misma fecha/hora.

## Requisitos

- Python 3.10 o superior
- pip
- (Opcional, solo para producción) Servidor MySQL 8+

## Instalación (desarrollo, con SQLite)

```bash
# 1. Entrar a la carpeta del proyecto
cd helen_spa

# 2. Crear entorno virtual
python -m venv venv
source venv/bin/activate        # En Windows: venv\Scripts\activate

# 3. Instalar dependencias
pip install -r requirements.txt

# 4. Aplicar migraciones (crea las tablas y carga los servicios iniciales)
python manage.py migrate

# 5. Crear usuario administrador
python manage.py createsuperuser

# 6. Ejecutar el servidor de desarrollo
python manage.py runserver
```

Abre `http://127.0.0.1:8000/` para ver el sitio y `http://127.0.0.1:8000/admin/`
para el panel de administración (gestionar servicios, citas y galería de fotos).

Con SQLite no necesitas instalar ni configurar ningún servidor de base de datos:
todo se guarda en el archivo `db.sqlite3` que se crea automáticamente.

## Cambiar a MySQL (producción)

1. Crea la base de datos y el usuario en tu servidor MySQL:

   ```sql
   CREATE DATABASE helen_spa CHARACTER SET utf8mb4;
   CREATE USER 'helen_spa_user'@'%' IDENTIFIED BY 'una-contraseña-segura';
   GRANT ALL PRIVILEGES ON helen_spa.* TO 'helen_spa_user'@'%';
   FLUSH PRIVILEGES;
   ```

2. Instala el conector de MySQL (requiere las librerías de desarrollo de MySQL
   instaladas en tu sistema, ej. `libmysqlclient-dev` en Ubuntu o MySQL Connector
   en Windows):

   ```bash
   pip install mysqlclient
   ```

   (también puedes descomentar la línea correspondiente en `requirements.txt`)

3. Copia `.env.example` como `.env` (o define las variables de entorno
   directamente) y ajusta:

   ```
   DJANGO_USE_MYSQL=True
   MYSQL_DATABASE=helen_spa
   MYSQL_USER=helen_spa_user
   MYSQL_PASSWORD=una-contraseña-segura
   MYSQL_HOST=127.0.0.1
   MYSQL_PORT=3306
   ```

4. Aplica las migraciones sobre la nueva base de datos:

   ```bash
   python manage.py migrate
   python manage.py createsuperuser
   ```

`settings.py` lee automáticamente `DJANGO_USE_MYSQL` para decidir entre SQLite
y MySQL, así que no es necesario modificar código.

## Estructura del proyecto

```
helen_spa/
├── manage.py
├── requirements.txt
├── .env.example
├── helen_spa/            # Configuración del proyecto (settings, urls)
└── spa/                  # App principal
    ├── models.py         # Servicio, Cita, GaleriaFoto
    ├── forms.py           # Formulario de agendamiento con validación de horario
    ├── views.py
    ├── urls.py
    ├── admin.py
    ├── migrations/        # Incluye carga inicial de los 8 servicios
    ├── templates/spa/     # inicio, servicios, quien_soy, agendamiento, galeria...
    └── static/spa/        # CSS y JS (estilo verde oscuro / dorado inspirado en tu flyer)
```

## Personalizar contenido

- **Textos "Quién soy"**: edita `spa/templates/spa/quien_soy.html`.
- **Servicios**: agrégalos, edítalos o desactívalos desde `/admin/` (sección
  Servicios), o modifica la migración `spa/migrations/0002_servicios_iniciales.py`
  antes de la primera migración.
- **Fotos de galería**: se suben desde `/admin/` → Galería de fotos. Aparecerán
  automáticamente en la página de Galería.
- **Datos de contacto (WhatsApp, teléfono, redes)**: edita el diccionario
  `NEGOCIO` en `helen_spa/settings.py`.
- **Colores y estilo**: `spa/static/spa/css/style.css` (variables `--verde-oscuro`,
  `--dorado`, `--crema` al inicio del archivo).

## Notas

- El contenido de "Quién soy" y los textos de ejemplo son marcadores de posición;
  reemplázalos con la información real de Helen.
- Este proyecto se generó y verificó (sintaxis y lógica) sin acceso a internet
  para instalar dependencias; antes de tu primera ejecución real, sigue los
  pasos de instalación anteriores para instalar Django y Pillow, y prueba el
  flujo completo de agendamiento (incluyendo intentar reservar en domingo o en
  un horario ya ocupado, para confirmar que las validaciones funcionan como
  esperas).
