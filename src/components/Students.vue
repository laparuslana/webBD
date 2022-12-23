<template>
  <h1 class="text-center">
    Students <span class="smaller">KPI University</span>
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
    <!--Show Add Student Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="studentData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Students Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Students ({{
            studentData.length
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
              Group
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Name
            </th>
            <th>
              <FIcons :icon="['fas', 'envelope']" />
              Email
            </th>
            <th>
              <FIcons :icon="['fas', 'phone']" />
              Phone
            </th>
            <th>
              <FIcons :icon="['fas', 'cog']" />
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-center" v-for="(student, i) in studentData" :key="i">
            <td>{{ student.id }}</td>
            <td>{{ student.group }}</td>
            <td>{{ student.name }}</td>
            <td>{{ student.email }}</td>
            <td>{{ student.phone }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectStudent(student);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectStudent(student);
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
    <!--Show Add New Student Modal-->
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
              <label for="studentGroupFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Group
              </label>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newStudent.group.$error }"
              >
                <v-select
                  class="v-select"
                  :options="GroupsList"
                  id="studentGroupFloat"
                  v-model="newStudent.group"
                />
                <span class="error-feedback" v-if="v$.newStudent.group.$error">
                  {{ v$.newStudent.group.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newStudent.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="studentNameFloat"
                  placeholder="John Smith"
                  v-model="newStudent.name"
                />
                <label for="studentNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Student Name
                </label>
                <span class="error-feedback" v-if="v$.newStudent.name.$error">
                  {{ v$.newStudent.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newStudent.email.$error }"
              >
                <input
                  type="email"
                  class="form-control"
                  id="studentEmailFloat"
                  placeholder="John Smith"
                  v-model="newStudent.email"
                />
                <label for="studentEmailFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  Student Email
                </label>
                <span class="error-feedback" v-if="v$.newStudent.email.$error">
                  {{ v$.newStudent.email.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newStudent.phone.$error }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="studentPhoneFloat"
                  placeholder="John Smith"
                  v-model="newStudent.phone"
                />
                <label for="studentPhoneFloat">
                  <FIcons :icon="['fas', 'phone']" />
                  Student Phone
                </label>
                <span class="error-feedback" v-if="v$.newStudent.phone.$error">
                  {{ v$.newStudent.phone.$errors[0].$message }}
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
    <!--Show Edit Student Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update User</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <label for="studentGroupFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Group
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentStudent.group.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="GroupsList"
                  id="studentGroupFloat"
                  v-model="currentStudent.group"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentStudent.group.$error"
                >
                  {{ v$.currentStudent.group.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentStudent.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="studentNameFloat"
                  placeholder="John Smith"
                  v-model="currentStudent.name"
                />
                <label for="studentNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Student Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentStudent.name.$error"
                >
                  {{ v$.currentStudent.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentStudent.email.$error }"
              >
                <input
                  type="email"
                  class="form-control"
                  id="studentEmailFloat"
                  placeholder="John Smith"
                  v-model="currentStudent.email"
                />
                <label for="studentEmailFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  Student Email
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentStudent.email.$error"
                >
                  {{ v$.currentStudent.email.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentStudent.phone.$error }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="studentPhoneFloat"
                  placeholder="John Smith"
                  v-model="currentStudent.phone"
                />
                <label for="studentPhoneFloat">
                  <FIcons :icon="['fas', 'phone']" />
                  Student Phone
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentStudent.phone.$error"
                >
                  {{ v$.currentStudent.phone.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current User
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Student Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Student</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this student?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentStudent.group
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentStudent.name
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'envelope']" />{{
                    currentStudent.email
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'phone']" />{{
                    currentStudent.phone
                  }}</span
                >
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current User
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
    <!--Show Delete All Students Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All Student</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all student?
              </h6>
              <p class="text-danger">
                Deleting all students will remove them from your system
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
import { required, email, minLength } from "@vuelidate/validators";
// eslint-disable-next-line no-unused-vars
import axios, { toFormData } from "axios";

export default {
  name: "RegisterUsers",
  components: {},
  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      studentData: [],
      GroupNameList: [],
      GroupsList: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newStudent: {
        group: "",
        name: "",
        email: "",
        phone: "",
      },
      currentStudent: {},
    };
  },
  validations() {
    return {
      newStudent: {
        group: { required },
        name: { required, minLength: minLength(2) },
        email: { required, email },
        phone: { required, minLength: minLength(10) },
      },
      currentStudent: {
        group: { required },
        name: { required, minLength: minLength(2) },
        email: { required, email },
        phone: { required, minLength: minLength(10) },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getStudentsData();
    this.getGroupsData();
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
    async getStudentsData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/students.php?action=read"
      );
      const restData = res.data;
      console.log(restData.students);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.studentData = restData.students;
        }
      }
    },
    async getGroupsData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/groups.php?action=read"
      );
      const restData = res.data;
      console.log(restData.groups);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.GroupNameList = restData.groups;
          this.GroupsList = [];
          this.GroupNameList.forEach((element) =>
            this.GroupsList.push(element.name)
          );
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/students.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.students);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.studentData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentStudent);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/students.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.students);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newStudentsDate = this.studentData.filter(
            (element) => element.id != this.currentStudent.id
          );
          console.table(newStudentsDate);
          this.successMsg = restData.message;
          this.currentStudent = {};
          this.studentData = newStudentsDate;
          // this.getStudentsData();
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/students.php"
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
      this.v$.newStudent.$validate();
      if (!this.v$.newStudent.$error) {
        this.addModalDialog(false);
        console.log("Add New User : Validated Successfully");
        let formData = this.toFormData(this.newStudent);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/students.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newStudent = { group: "", name: "", email: "", phone: "" };
            this.successMsg = restData.message;
            this.getStudentsData();
            this.v$.newStudent.$reset();
          }
        }
      } else {
        console.log("Add New User : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentStudent.$validate();
      if (!this.v$.currentStudent.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentStudent);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/students.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.students);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentStudent = {};
            this.successMsg = restData.message;
            this.getStudentsData();
            this.v$.currentStudent.$reset();
          }
        }
        console.log("Update User : Validated Successfully");
      } else {
        console.log("Update User : Not Validated");
      }
    },
    selectStudent(std) {
      this.currentStudent = std;
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
