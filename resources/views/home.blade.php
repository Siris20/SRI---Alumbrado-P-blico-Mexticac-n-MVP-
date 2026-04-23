@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">

</head>
<body>
    <div class="home-container">

    <!-- HERO -->
    <section class="hero">
        <img src="/images/hero.webp" alt="Alumbrado público">

        <div class="hero-content">
            <h1>REPORTE DE INCIDENCIAS<br>DE ALUMBRADO PÚBLICO</h1>
            <p><em>“Enciende tu calle en un click”</em></p>

            <a href="/reportes" class="btn-reportar">
                ¡LEVANTAR REPORTE!
            </a>
        </div>
    </section>

    <!-- ACERCA -->
    <section class="section">
        <h2 class="section-title">Acerca de este sistema</h2>

        <div class="mision">
            <div>
                <h3>Misión</h3>
                <p>
                    En el Municipio de Mexicanacan, nos comprometemos a mejorar
                    la calidad de vida de nuestros ciudadanos mediante la
                    implementación de un sistema eficiente de reportes de
                    alumbrado público que permita la identificación y atención
                    rápida de incidencias.
                </p>
            </div>

            <div>
                <img src="/images/mision.webp" alt="Alumbrado municipal">
            </div>
        </div>
    </section>

    <!-- QUE ES -->
    <section class="section">
        <h2 class="section-title">¿Qué es este sistema?</h2>

        <p>
            Este sistema permite a los ciudadanos reportar incidencias en el
            alumbrado público de manera rápida y sencilla, proporcionando
            ubicación, evidencia fotográfica y descripción del problema para
            facilitar su atención por parte del municipio.
        </p>
    </section>

    <!-- COMPROMISO -->
    <section class="section">
        <h2 class="section-title">Nuestro Compromiso</h2>

        <div class="compromiso">
            <div>
                <h3>Para los ciudadanos</h3>
                <p>
                    Facilitar un mecanismo accesible y transparente para
                    reportar fallas, dar seguimiento a sus reportes y mejorar la
                    seguridad en su comunidad.
                </p>
                <img src="/images/ciudadanos.webp" alt="Ciudadanos">
            </div>

            <div>
                <h3>Para el municipio</h3>
                <p>
                    Optimizar los procesos de mantenimiento del alumbrado
                    público, priorizar incidencias y mejorar la gestión de
                    recursos municipales.
                </p>
                <img src="/images/municipio.webp" alt="Municipio">
            </div>
        </div>
    </section>

</div>

</body>
</html>
@endsection