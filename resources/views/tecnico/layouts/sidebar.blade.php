<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route(auth()->user()->role . '.dashboard') }}">fsanchez.dev</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route(auth()->user()->role . '.dashboard') }}">fs</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <!-- <li><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="far fa-square"></i> <span>Dashboard</span></a></li> -->
      <li class="dropdown active">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        <ul class="dropdown-menu">
          <li class=active><a class="nav-link" href="{{ route('tecnico.dashboard') }}">Inicio</a></li>
          <li class=active><a class="nav-link" href="{{ route('tecnico.visitas') }}"">Visitas</a></li>
        </ul>
      </li>
    </ul>
  </aside>
</div>