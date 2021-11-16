<template>
<div>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link to="/clients" class="nav-link"> <strong>Clientes</strong> </router-link>
                    </li>
                     <li class="nav-item">
                        <router-link to="/products" class="nav-link"> <strong>Productos</strong> </router-link>
                    </li>
                     <li class="nav-item">
                        <router-link to="/sales" class="nav-link"> <strong>Ventas</strong> </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
          <router-view></router-view>
        </div>
    </main>
</div>
</template>

