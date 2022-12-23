<template>
  <h1 class="text-center">
    Departments <span class="smaller">KPI University</span>
  </h1>
  <!--Show Error Message--><!--Show Success Message-->
  <div class="container" v-if="!isDBConnected">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      {{ isDBConnectedMsg }}
      Error Message
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
      ></button>
    </div>
  </div>
  <div class="container" v-else>
    <div
      class="alert alert-danger alert-dismissible fade show"
      v-if="errMsg"
      role="alert"
    >
      {{ errMsg }}
      Error Message
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
      ></button>
    </div>
    <div
      class="alert alert-success alert-dismissible fade show"
      v-if="successMsg"
      role="alert"
    >
      {{ successMsg }}
      <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert"
        aria-label="Close"
      ></button>
    </div>
    <!--Show Add Department Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="DepartmentData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Departments Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Departments ({{
            DepartmentData.length
          }})
        </caption>
        <thead>
          <tr class="bg-success text-light text-center">
            <th>
              <FIcons :icon="['fas', 'id-badge']" />
              ID
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Faculty
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Name
            </th>
            <th>
              <FIcons :icon="['far', 'user']" />
              Short Name
            </th>
            <th>
              <FIcons :icon="['fas', 'cog']" />
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="text-center"
            v-for="(department, i) in DepartmentData"
            :key="i"
          >
            <td>{{ department.id }}</td>
            <td>{{ department.faculty }}</td>
            <td>{{ department.name }}</td>
            <td>{{ department.short_name }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectDepartment(department);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectDepartment(department);
                "
              >
                <FIcons :icon="['fas', 'user-edit']" />
                Update
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--Show Add New Department Modal-->
    <div id="overlay" v-if="showAddModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info">Add New User</h5>
            <button aria-hidden="true" @click="addModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <!--v-select-->
              <label for="departmentFacultyFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Faculty
              </label>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newDepartment.faculty.$error }"
              >
                <v-select
                  class="v-select"
                  :options="FacultiesList"
                  id="departmentFacultyFloat"
                  v-model="newDepartment.faculty"
                />
                <span
                  class="error-feedback"
                  v-if="v$.newDepartment.faculty.$error"
                >
                  {{ v$.newDepartment.faculty.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newDepartment.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="departmentNameFloat"
                  placeholder="John Smith"
                  v-model="newDepartment.name"
                />
                <label for="departmentNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Department Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.newDepartment.name.$error"
                >
                  {{ v$.newDepartment.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.newDepartment.short_name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="departmentShortNameFloat"
                  placeholder="John Smith"
                  v-model="newDepartment.short_name"
                />
                <label for="departmentShortNameFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Short Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.newDepartment.short_name.$error"
                >
                  {{ v$.newDepartment.short_name.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-info" @click="addNewUser()">
                  Add New User
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Edit Department Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update Department</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <!--v-select-->
              <label for="departmentFacultyFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Faculty
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentDepartment.faculty.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="FacultiesList"
                  id="departmentFacultyFloat"
                  v-model="currentDepartment.faculty"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentDepartment.faculty.$error"
                >
                  {{ v$.currentDepartment.faculty.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentDepartment.name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="departmentNameFloat"
                  placeholder="John Smith"
                  v-model="currentDepartment.name"
                />
                <label for="departmentNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Department Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentDepartment.name.$error"
                >
                  {{ v$.currentDepartment.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentDepartment.short_name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="departmentShortNameFloat"
                  placeholder="John Smith"
                  v-model="currentDepartment.short_name"
                />
                <label for="departmentShortNameFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Short Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentDepartment.short_name.$error"
                >
                  {{ v$.currentDepartment.short_name.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current Department
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Department Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete Current Department</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this department?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentDepartment.faculty
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentDepartment.name
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['far', 'user']" />{{
                    currentDepartment.short_name
                  }}</span
                ><br />
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current Department
                </button>
                <button
                  class="btn btn-success"
                  @click="deleteModalDialog(false)"
                >
                  No, don't delete
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete All Departments Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all departments?
              </h6>
              <p class="text-danger">
                Deleting all departments will remove them from your system
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteAllUsers()">
                  Delete All
                </button>
                <button
                  class="btn btn-success"
                  @click="deleteAllModalDialog(false)"
                >
                  No, don't delete
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import useValidate from "@vuelidate/core";
import { required, minLength } from "@vuelidate/validators";
// eslint-disable-next-line no-unused-vars
import axios, { toFormData } from "axios";

export default {
  name: "Departments-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      DepartmentData: [],
      FacultyNameList: [],
      FacultiesList: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newDepartment: {
        faculty: "",
        name: "",
        short_name: "",
      },
      currentDepartment: {},
    };
  },
  validations() {
    return {
      newDepartment: {
        name: { required, minLength: minLength(5) },
        short_name: { required, minLength: minLength(5) },
        faculty: { required },
      },
      currentDepartment: {
        name: { required, minLength: minLength(5) },
        short_name: { required, minLength: minLength(5) },
        faculty: { required },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getDepartmentsData();
    this.getFacultiesData();
  },
  methods: {
    addModalDialog(val) {
      this.showAddModal = val;
    },
    updateModalDialog(val) {
      this.showUpdateModal = val;
    },
    deleteModalDialog(val) {
      this.showDeleteModal = val;
    },
    deleteAllModalDialog(val) {
      this.showDeleteAllModal = val;
    },
    async getDepartmentsData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/departmets.php?action=read"
      );
      const restData = res.data;
      console.log(restData.departaments);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.DepartmentData = restData.departaments;
        }
      }
    },
    async getFacultiesData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faculty.php?action=read"
      );
      const restData = res.data;
      console.log(restData.faculties);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.FacultyNameList = restData.faculties;
          this.FacultiesList = [];
          this.FacultyNameList.forEach((element) =>
            this.FacultiesList.push(element.short_name)
          );
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/departmets.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.departaments);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.DepartmentData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentDepartment);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/departmets.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.departaments);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newDepartmentsData = this.DepartmentData.filter(
            (element) => element.id != this.currentDepartment.id
          );
          console.table(newDepartmentsData);
          this.successMsg = restData.message;
          this.currentDepartment = {};
          this.DepartmentData = newDepartmentsData;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/departmets.php"
      );
      const restData = res.data;
      this.isDBConnected = restData.is_db_connected;
      this.isDBConnectedMsg = restData.connection_msg;
    },
    toFormData(obj) {
      var formData = new FormData();
      for (var i in obj) {
        console.log(i + " => " + obj[i]);
        formData.append(i, obj[i]);
      }
      return formData;
    },
    async addNewUser() {
      this.clearOldMsgs();
      // this.addModalDialog(false);
      this.v$.newDepartment.$validate();
      if (!this.v$.newDepartment.$error) {
        this.addModalDialog(false);
        console.log("Add New Department : Validated Successfully");
        let formData = this.toFormData(this.newDepartment);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/departmets.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newDepartment = { faculty: "", name: "", short: "" };
            this.successMsg = restData.message;
            this.getDepartmentsData();
            this.v$.newDepartment.$reset();
          }
        }
      } else {
        console.log("Add New Department : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentDepartment.$validate();
      if (!this.v$.currentDepartment.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentDepartment);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/departmets.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.departaments);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentDepartment = {};
            this.successMsg = restData.message;
            this.successMsg = restData.message;
            this.getDepartmentsData();
            this.v$.currentDepartment.$reset();
          }
        }
        console.log("Update Department : Validated Successfully");
      } else {
        console.log("Update Department : Not Validated");
      }
    },
    selectDepartment(dep) {
      this.currentDepartment = dep;
    },
  },
};
</script>
<style scoped>
@import "vue-select/dist/vue-select.css";
.label-for-vselect {
  background-color: white;
}
.v-select {
  border: white;
  background-color: white;
}
h1 {
  background-color: cadetblue;
  color: white;
  padding: 8px;
  margin-top: 0;
  margin-bottom: 50px;
}

.smaller {
  font-size: 0.85rem;
}

#overlay {
  position: fixed;
  top: 0;
  button: 0;
  left: 0;
  right: 0;
  background-color: rgb(0 0 0 /70%);
}

.form-group-error {
  color: red;
}

.form-group-error input {
  border-color: red;
}
</style>
