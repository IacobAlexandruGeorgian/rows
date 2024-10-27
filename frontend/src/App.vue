<template>
  <div>
    <b-navbar type="dark" variant="dark" class="p-2">

      <b-navbar-brand>ROWS HR</b-navbar-brand>

      <b-navbar-nav class="mx-auto">
        <b-nav-item to="/employees" exact exact-active-class="active">Employees</b-nav-item>
        <b-nav-item to="/projects" exact exact-active-class="active">Projects</b-nav-item>
      </b-navbar-nav>

    </b-navbar>

    <b-container class="mt-4 text-center">
      <router-view></router-view>
    </b-container>
  </div>
</template>

<script>
export default {
  name: 'App',
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
