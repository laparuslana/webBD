<template>
  <h1 class="text-center">
    Faculties <span class="smaller">KPI University</span>
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
    <!--Show Add Faculty Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="FacultyData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Faculties Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Faculties ({{
            FacultyData.length
          }})
        </caption>
        <thead>
          <tr class="bg-success text-light text-center">
            <th>
              <FIcons :icon="['fas', 'id-badge']" />
              ID
            </th>
            <th>Name</th>
            <th>Short Name</th>
            <th>
              <FIcons :icon="['fas', 'cog']" />
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-center" v-for="(faculty, i) in FacultyData" :key="i">
            <td>{{ faculty.id }}</td>
            <td>{{ faculty.name }}</td>
            <td>{{ faculty.short_name }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectFaculty(faculty);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectFaculty(faculty);
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
    <!--Show Add New Faculty Modal-->
    <div id="overlay" v-if="showAddModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info">Add New</h5>
            <button aria-hidden="true" @click="addModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newFaculty.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="facultyNameFloat"
                  placeholder="John Smith"
                  v-model="newFaculty.name"
                />
                <label for="facultyNameFloat"> Faculty Name </label>
                <span class="error-feedback" v-if="v$.newFaculty.name.$error">
                  {{ v$.newFaculty.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newFaculty.short_name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="facultyShortNameFloat"
                  placeholder="John Smith"
                  v-model="newFaculty.short_name"
                />
                <label for="facultyShortNameFloat"> Short Name </label>
                <span
                  class="error-feedback"
                  v-if="v$.newFaculty.short_name.$error"
                >
                  {{ v$.newFaculty.short_name.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-info" @click="addNewUser()">
                  Add New
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Edit Faculty Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update Faculty</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentFaculty.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="facultyNameFloat"
                  placeholder="John Smith"
                  v-model="currentFaculty.name"
                />
                <label for="facultyNameFloat"> Faculty Name </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentFaculty.name.$error"
                >
                  {{ v$.currentFaculty.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentFaculty.short_name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="facultyShortNameFloat"
                  placeholder="John Smith"
                  v-model="currentFaculty.short_name"
                />
                <label for="facultyShortNameFloat"> Short Name </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentFaculty.short_name.$error"
                >
                  {{ v$.currentFaculty.short_name.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current Faculty
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Faculty Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Faculty</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this faculty?
              </h6>
              <p>
                <span>{{ currentFaculty.name }}</span
                ><br />
                <span>{{ currentFaculty.short_name }}</span
                ><br />
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current Faculty
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
    <!--Show Delete All Faculties Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All Faculties</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all faculties?
              </h6>
              <p class="text-danger">
                Deleting all faculties will remove them from your system
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
  name: "Faculties-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      FacultyData: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newFaculty: {
        name: "",
        short_name: "",
      },
      currentFaculty: {},
    };
  },
  validations() {
    return {
      newFaculty: {
        name: { required, minLength: minLength(3) },
        short_name: { required, minLength: minLength(3) },
      },
      currentFaculty: {
        name: { required, minLength: minLength(3) },
        short_name: { required, minLength: minLength(3) },
      },
    };
  },
  mounted() {
    this.dbConnection();
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
          this.FacultyData = restData.faculties;
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faculty.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.faculties);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.FacultyData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentFaculty);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/faculty.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.faculties);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newFacultiesData = this.FacultyData.filter(
            (element) => element.id != this.currentFaculty.id
          );
          console.table(newFacultiesData);
          this.successMsg = restData.message;
          this.currentFaculty = {};
          this.FacultyData = newFacultiesData;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faculty.php"
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
      this.v$.newFaculty.$validate();
      if (!this.v$.newFaculty.$error) {
        this.addModalDialog(false);
        console.log("Add New Faculty : Validated Successfully");
        let formData = this.toFormData(this.newFaculty);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/faculty.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newFaculty = { name: "", short_name: "" };
            this.successMsg = restData.message;
            this.getFacultiesData();
            this.v$.newFaculty.$reset();
          }
        }
      } else {
        console.log("Add New Faculty : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentFaculty.$validate();
      if (!this.v$.currentFaculty.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentFaculty);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/faculty.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.faculties);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentFaculty = {};
            this.successMsg = restData.message;
            this.getFacultiesData();
            this.v$.currentFaculty.$reset();
          }
        }
        console.log("Update Faculty : Validated Successfully");
      } else {
        console.log("Update Faculty : Not Validated");
      }
    },
    selectFaculty(std) {
      this.currentFaculty = std;
    },
  },
};
</script>
<style scoped>
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
