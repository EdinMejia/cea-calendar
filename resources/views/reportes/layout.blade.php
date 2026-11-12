<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        @page { margin: 74px 34px 52px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #111; }
        table { width: 100%; }

        header { position: fixed; top: -62px; left: 0; right: 0; background: #000; border-bottom: 4px solid #83C152; }
        header td { padding: 9px 12px; color: #fff; font-size: 8px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        header td.der { text-align: right; color: #83C152; }

        footer { position: fixed; bottom: -34px; left: 0; right: 0; padding-top: 4px; border-top: 2px solid #000; font-size: 8px; color: #444; }
        footer td.der { text-align: right; font-weight: bold; color: #000; }
        .pag:before { content: "Página " counter(page); }

        .hero { border-collapse: separate; border-spacing: 0; margin-bottom: 16px; }
        .hero td { padding: 14px 16px; background: #83C152; border: 2px solid #000; border-right-width: 6px; border-bottom-width: 6px; }
        .hero .kick { font-size: 8px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .hero .tit  { margin: 3px 0; font-size: 22px; font-weight: bold; line-height: 1.1; }
        .hero .per  { font-size: 11px; }

        h2 { margin: 16px 0 8px; padding: 5px 10px; background: #000; color: #fff; border-left: 8px solid #83C152; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; }
        .bloque { page-break-inside: avoid; }
        .vacio  { padding: 22px; text-align: center; font-weight: bold; color: #555; }

        .stats { border-collapse: separate; border-spacing: 0; margin-bottom: 14px; }
        .stats td { padding: 8px 6px; background: #fff; text-align: center; border: 2px solid #000; border-top: 5px solid #83C152; border-bottom-width: 5px; border-left-width: 0; }
        .stats td.pri { border-left-width: 2px; }
        .stats td.ult { border-right-width: 5px; }
        .stats .n { font-size: 22px; font-weight: bold; line-height: 1; }
        .stats .e { margin-top: 3px; font-size: 7px; letter-spacing: 1px; text-transform: uppercase; color: #555; }

        .dia { border-collapse: separate; border-spacing: 0; margin-bottom: 12px; page-break-inside: avoid; border: 2px solid #000; border-right-width: 5px; border-bottom-width: 5px; }
        .dia td { padding: 6px 8px; vertical-align: middle; border-bottom: 1px solid #bbb; }
        .dia tr.ult td { border-bottom: 0; }
        .dia tr.alt td { background: #f3f4f6; }
        .dia td.fecha { width: 54px; padding: 6px 2px; background: #000; color: #fff; text-align: center; border-bottom: 0; }
        .fecha .sem, .fecha .mes { font-size: 8px; font-weight: bold; letter-spacing: 1.5px; text-transform: uppercase; }
        .fecha .num { font-size: 24px; font-weight: bold; line-height: 1.1; color: #83C152; }
        .dia td.hora { width: 74px; border-right: 1px solid #bbb; }
        .hora .ini { font-size: 11px; font-weight: bold; }
        .hora .fin { font-size: 8px; color: #555; }
        .act .nom { font-size: 11px; font-weight: bold; }
        .act .lug { margin-top: 2px; font-size: 9px; color: #555; }
        .dia td.com { width: 112px; text-align: right; }
        .chip { display: inline-block; padding: 2px 7px; border: 1.5px solid #000; font-size: 8px; font-weight: bold; text-transform: uppercase; }

        .barras { border-collapse: collapse; }
        .barras td { padding: 3px 4px; vertical-align: middle; }
        .barras .etq   { width: 130px; font-size: 9px; font-weight: bold; }
        .barras .pista { width: 300px; background: #e5e7eb; border: 1.5px solid #000; }
        .barras .barra { height: 13px; }
        .barras .num   { width: 70px; text-align: right; font-weight: bold; }

        .nombre { border-collapse: separate; border-spacing: 0; margin-bottom: 14px; }
        .nombre td { padding: 14px 16px; text-align: center; border: 2px solid #000; border-right-width: 6px; border-bottom-width: 6px; }
        .nombre .kick { font-size: 8px; font-weight: bold; letter-spacing: 2px; text-transform: uppercase; }
        .nombre .tit  { margin-top: 3px; font-size: 18px; font-weight: bold; text-transform: uppercase; line-height: 1.2; }

        .rejilla { border-collapse: separate; border-spacing: 0; }
        .rejilla td { width: 50%; padding: 0 0 10px; vertical-align: top; }
        .rejilla td.izq { padding-right: 5px; }
        .rejilla td.der { padding-left: 5px; }
        .etq { margin-bottom: 2px; font-size: 8px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; color: #555; }
        .val { padding: 7px 9px; background: #fff; border: 2px solid #000; border-right-width: 4px; border-bottom-width: 4px; font-size: 12px; font-weight: bold; }
        .val.desc { min-height: 60px; font-size: 11px; font-weight: normal; }

        .lista { border-collapse: collapse; margin-top: 4px; }
        .lista th { padding: 5px 7px; background: #e5e7eb; border: 1.5px solid #000; text-align: left; font-size: 8px; letter-spacing: 1px; text-transform: uppercase; }
        .lista td { padding: 9px 7px; border: 1px solid #000; }
        .lista tr.alt td { background: #f3f4f6; }
        .lista .num   { width: 34px; text-align: center; }
        .lista .firma { width: 160px; }
    </style>
</head>
<body>
    <header>
        <table><tr>
            <td>Centro de Estudios Avanzados de Totonicapán</td>
            <td class="der">{{ $titulo }}</td>
        </tr></table>
    </header>

    <footer>
        <table><tr>
            <td>Generado el {{ $generado }} por {{ $usuario }}</td>
            <td class="der pag"></td>
        </tr></table>
    </footer>

    <table class="hero"><tr><td>
        <div class="kick">Calendario escolar CEA</div>
        <div class="tit">{{ $titulo }}</div>
        <div class="per">{{ $periodo }}</div>
    </td></tr></table>

    @yield('contenido')
</body>
</html>