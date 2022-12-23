<template>
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid header">
      <img src="../../photos/logo.png" class="logo" height="45" alt="Brand" />
      <div class="navbar-brand">Campus</div>
      <button
        type="button"
        class="navbar-toggler"
        data-bs-toggle="collapse"
        data-bs-target="#navbarCollapse"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto">
          <router-link to="/teachers" class="nav-item nav-link"
            >Teachers</router-link
          >
          <router-link to="/faculties" class="nav-item nav-link"
            >Faculties</router-link
          >
          <router-link to="/disciplines" class="nav-item nav-link"
            >Disciplines</router-link
          >
          <router-link to="/students" class="nav-item nav-link"
            >Students</router-link
          >
          <router-link to="/departments" class="nav-item nav-link"
            >Departments</router-link
          >
          <router-link to="/groups" class="nav-item nav-link"
            >Groups</router-link
          >
          <router-link to="/schedule" class="nav-item nav-link"
            >Schedule</router-link
          >
          <router-link to="/faq" class="nav-item nav-link">FAQ</router-link>
        </div>
      </div>
    </div>
  </nav>

  <!--  <router-link to="/teachers">Teachers</router-link>-->
  <!--  <router-link to="/faculties">Faculties</router-link>-->
  <!--  <router-link to="/disciplines">Disciplines</router-link>-->
  <!--  <router-link to="/students">Students</router-link>-->
  <!--  <router-link to="/departments">Departments</router-link>-->
  <!--  <router-link to="/groups">Groups</router-link>-->
  <!--  <router-link to="/schedule">Schedule</router-link>-->
</template>

<script>
export default {
  name: "Header-component",
};
</script>
