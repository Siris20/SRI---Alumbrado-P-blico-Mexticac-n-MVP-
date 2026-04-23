@extends('layouts.app')

@section('title', 'Contacto')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>contacto</title>
  
  <link href="{{ asset('css/contacto.css') }}" rel="stylesheet">

</head>
<body>
 <main class="contacto-section">
  <div class="container">
    <h1 class="main-title">Información de contacto</h1>

    <section class="contact-block">
      <h2 class="subtitle">Para Reportes de Alumbrado Público</h2>
      <ul>
        <li><strong>Aplicación Móvil:</strong> Disponible para iOS y Android</li>
        <li><strong>Portal Web:</strong> [enlace a la aplicación web]</li>
        <li><strong>Línea Directa de Reportes:</strong> [número telefónico específico]</li>
      </ul>
    </section>

    <section class="contact-block">
      <h2 class="subtitle">Para Soporte Técnico de la Aplicación</h2>
      <ul>
        <li><strong>Equipo de Desarrollo:</strong> [correo del equipo técnico]</li>
        <li><strong>Reportar Problemas Técnicos:</strong> [formulario específico en la app]</li>
      </ul>
    </section>

    <section class="contact-block">
      <h2 class="subtitle">Para Consultas Generales</h2>
      <ul>
        <li><strong>Correo General:</strong> [correo general del municipio]</li>
        <li><strong>Fax:</strong> [número de fax si aplica]</li>
      </ul>
    </section>

    <section class="contact-block social">
      <h2 class="subtitle">Redes Sociales Oficiales</h2>
      <div class="social-links">
        <a href="#" aria-label="Facebook oficial">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook">
          Facebook: [enlace al Facebook oficial]
        </a>
        <a href="#" aria-label="Twitter oficial">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" alt="Twitter">
          Twitter: [cuenta oficial de Twitter]
        </a>
        <a href="#" aria-label="Instagram oficial">
          <img src="https://cdn-icons-png.flaticon.com/512/733/733558.png" alt="Instagram">
          Instagram: [cuenta oficial de Instagram]
        </a>
      </div>
    </section>
  </div>
 </main>
</body>
</html>
 
@endsection
