## Pasos
<ul>
  <li>Clonar el proyecto en el comando <code>git clone</code> ruta SSH o HTTPS</li>
  <li>Crear el archivo .env con los comandos
    <ul>
      <li><code>cp .env.example .env</code></li>
      <li><code>php artisan key:generate</code></li>
    </ul>
  </li>  
  <li>Configurar la conexión de la base de datos en el archivo .env</li>
  <li>Ejecutar los comandos en orden
    <ul>
      <li><code>composer update</code></li>
      <li><code>npm i</code></li>
      <li><code>php artisan migrate</code></li>
    </ul>
  </li>
</ul>
