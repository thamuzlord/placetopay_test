## Pasos
<ul>
  <li>Clonar el proyecto en el comando <code>git clone</code> ruta SSH o HTTPS</li> 
  <li>Configurar la conexión de la base de datos en el archivo .env</li>
  <li>Ejecutar los comandos en orden
    <ul>
      <li><code>composer update</code></li>
      <li><code>npm i</code></li>
      <li><code>php artisan migrate</code></li>
    </ul>
  </li>
  <li>Ejecutar el siguiente script para insertar los productos en la base de datos</li>
  <code>
    insert  into `product`(`id`,`product_code`,`product_description`,`product_cost`) values 
    (1,'001','HOUSE OF CARDS',285999),
    (2,'002','HANNIBAL',250999),
    (3,'003','THE BIG BANG THEORY',128999),
    (4,'004','THE GOOD DOCTOR',152999),
    (5,'005','THE SIMPSONS',199999);
  </code>
</ul>
