@extends('tenant.layouts.app')

@section('content')

    <div class="row tab-content-default row-new bg-transparent">

        <div class="page-header pr-0">
            <h2><a href="/suscription/grade_section">
            <svg  xmlns="http://www.w3.org/2000/svg" style="margin-top: -5px;" width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v4.5" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M19 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M22 22a2 2 0 0 0 -2 -2h-2a2 2 0 0 0 -2 2" /></svg>
            </a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Grados y secciones</span></li>
            </ol>
        </div>

        <div class="col-md-6 ui-sortable">
            <tenant-suscription-grades-index></tenant-suscription-grades-index>
        </div>
        <div class="col-md-6 ui-sortable">
            <tenant-suscription-sections-index></tenant-suscription-sections-index>
        </div>

    </div>

@endsection
