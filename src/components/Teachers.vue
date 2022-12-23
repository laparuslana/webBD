<template>
  <h1 class="text-center">
    Teachers <span class="smaller">KPI University</span>
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
    <!--Show Add Teacher Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="TeacherData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Teachers Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Teachers ({{
            TeacherData.length
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
              Name
            </th>
            <th>
              <FIcons :icon="['far', 'user']" />
              Surname
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
          <tr class="text-center" v-for="(teacher, i) in TeacherData" :key="i">
            <td>{{ teacher.id }}</td>
            <td>{{ teacher.name }}</td>
            <td>{{ teacher.surname }}</td>
            <td>{{ teacher.email }}</td>
            <td>{{ teacher.phone }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectTeacher(teacher);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectTeacher(teacher);
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
    <!--Show Add New Teacher Modal-->
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
                :class="{ 'form-group-error': v$.newTeacher.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="teacherNameFloat"
                  placeholder="John Smith"
                  v-model="newTeacher.name"
                />
                <label for="teacherNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Teacher Name
                </label>
                <span class="error-feedback" v-if="v$.newTeacher.name.$error">
                  {{ v$.newTeacher.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newTeacher.surname.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="teacherSurnameFloat"
                  placeholder="John Smith"
                  v-model="newTeacher.surname"
                />
                <label for="teacherSurnameFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Teacher Surname
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.newTeacher.surname.$error"
                >
                  {{ v$.newTeacher.surname.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newTeacher.email.$error }"
              >
                <input
                  type="email"
                  class="form-control"
                  id="teacherEmailFloat"
                  placeholder="John Smith"
                  v-model="newTeacher.email"
                />
                <label for="teacherEmailFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  Teacher Email
                </label>
                <span class="error-feedback" v-if="v$.newTeacher.email.$error">
                  {{ v$.newTeacher.email.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newTeacher.phone.$error }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="teacherPhoneFloat"
                  placeholder="John Smith"
                  v-model="newTeacher.phone"
                />
                <label for="teacherPhoneFloat">
                  <FIcons :icon="['fas', 'phone']" />
                  Teacher Phone
                </label>
                <span class="error-feedback" v-if="v$.newTeacher.phone.$error">
                  {{ v$.newTeacher.phone.$errors[0].$message }}
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
    <!--Show Edit Teacher Modal-->
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
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentTeacher.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="teacherNameFloat"
                  placeholder="John Smith"
                  v-model="currentTeacher.name"
                />
                <label for="teacherNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Teacher Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentTeacher.name.$error"
                >
                  {{ v$.currentTeacher.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentTeacher.surname.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="teacherSurnameFloat"
                  placeholder="John Smith"
                  v-model="currentTeacher.surname"
                />
                <label for="teacherSurnameFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Teacher Surname
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentTeacher.surname.$error"
                >
                  {{ v$.currentTeacher.surname.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentTeacher.email.$error }"
              >
                <input
                  type="email"
                  class="form-control"
                  id="teacherEmailFloat"
                  placeholder="John Smith"
                  v-model="currentTeacher.email"
                />
                <label for="teacherEmailFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  Teacher Email
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentTeacher.email.$error"
                >
                  {{ v$.currentTeacher.email.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentTeacher.phone.$error }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="teacherPhoneFloat"
                  placeholder="John Smith"
                  v-model="currentTeacher.phone"
                />
                <label for="teacherPhoneFloat">
                  <FIcons :icon="['fas', 'phone']" />
                  Teacher Phone
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentTeacher.phone.$error"
                >
                  {{ v$.currentTeacher.phone.$errors[0].$message }}
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
    <!--Show Delete Teacher Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Teacher</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this teacher?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentTeacher.name
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['far', 'user']" />{{
                    currentTeacher.surname
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'envelope']" />{{
                    currentTeacher.email
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'phone']" />{{
                    currentTeacher.phone
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
    <!--Show Delete All Teachers Modal-->
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
                Are you sure you want to delete all teacher?
              </h6>
              <p class="text-danger">
                Deleting all teachers will remove them from your system
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
  name: "Teachers-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      TeacherData: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newTeacher: {
        name: "",
        surname: "",
        email: "",
        phone: "",
      },
      currentTeacher: {},
    };
  },
  validations() {
    return {
      newTeacher: {
        name: { required, minLength: minLength(2) },
        surname: { required, minLength: minLength(2) },
        email: { required, email },
        phone: { required, minLength: minLength(10) },
      },
      currentTeacher: {
        name: { required, minLength: minLength(2) },
        surname: { required, minLength: minLength(2) },
        email: { required, email },
        phone: { required, minLength: minLength(10) },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getTeachersData();
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
    async getTeachersData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/teachers.php?action=read"
      );
      const restData = res.data;
      console.log(restData.teachers);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.TeacherData = restData.teachers;
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/teachers.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.teachers);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.TeacherData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentTeacher);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/teachers.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.teachers);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newTeachersDate = this.TeacherData.filter(
            (element) => element.id != this.currentTeacher.id
          );
          console.table(newTeachersDate);
          this.successMsg = restData.message;
          this.currentTeacher = {};
          this.TeacherData = newTeachersDate;
          // this.getTeachersData();
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/teachers.php"
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
      this.v$.newTeacher.$validate();
      if (!this.v$.newTeacher.$error) {
        this.addModalDialog(false);
        console.log("Add New User : Validated Successfully");
        let formData = this.toFormData(this.newTeacher);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/teachers.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newTeacher = {
              name: "",
              short_name: "",
              email: "",
              phone: "",
            };
            this.successMsg = restData.message;
            this.getTeachersData();
            this.v$.newTeacher.$reset();
          }
        }
      } else {
        console.log("Add New User : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentTeacher.$validate();
      if (!this.v$.currentTeacher.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentTeacher);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/teachers.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.teachers);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentTeacher = {};
            this.successMsg = restData.message;
            this.getTeachersData();
            this.v$.currentTeacher.$reset();
          }
        }
        console.log("Update User : Validated Successfully");
      } else {
        console.log("Update User : Not Validated");
      }
    },
    selectTeacher(std) {
      this.currentTeacher = std;
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
