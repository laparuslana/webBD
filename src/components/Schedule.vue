<template>
  <h1 class="text-center">
    Schedule <span class="smaller">KPI University</span>
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
    <!--Show Add schedule Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="scheduleData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show schedule Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered schedule ({{
            scheduleData.length
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
              Teacher
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Discipline
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Name
            </th>
            <th>
              <FIcons :icon="['fas', 'envelope']" />
              Time
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Classroom
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
            v-for="(schedule, i) in scheduleData"
            :key="i"
          >
            <td>{{ schedule.id }}</td>
            <td>{{ schedule.group }}</td>
            <td>{{ schedule.teacher }}</td>
            <td>{{ schedule.discipline }}</td>
            <td>{{ schedule.name }}</td>
            <td>{{ schedule.time }}</td>
            <td>{{ schedule.classroom }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectSchedule(schedule);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectSchedule(schedule);
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
    <!--Show Add New schedule Modal-->
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
              <label for="scheduleGroupFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Group
              </label>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newSchedule.group.$error }"
              >
                <v-select
                  class="v-select"
                  :options="GroupsList"
                  id="scheduleGroupFloat"
                  v-model="newSchedule.group"
                />
                <span class="error-feedback" v-if="v$.newSchedule.group.$error">
                  {{ v$.newSchedule.group.$errors[0].$message }}
                </span>
              </div>
              <label for="scheduleTeacherFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Teacher
              </label>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newSchedule.teacher.$error }"
              >
                <v-select
                  class="v-select"
                  :options="TeachersList"
                  id="scheduleTeacherFloat"
                  v-model="newSchedule.teacher"
                />
                <span
                  class="error-feedback"
                  v-if="v$.newSchedule.teacher.$error"
                >
                  {{ v$.newSchedule.teacher.$errors[0].$message }}
                </span>
              </div>
              <label for="scheduleDisciplineFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Discipline
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.newSchedule.discipline.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="DisciplinesList"
                  id="scheduleDisciplineFloat"
                  v-model="newSchedule.discipline"
                />
                <span
                  class="error-feedback"
                  v-if="v$.newSchedule.discipline.$error"
                >
                  {{ v$.newSchedule.discipline.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newSchedule.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="scheduleNameFloat"
                  placeholder="John Smith"
                  v-model="newSchedule.name"
                />
                <label for="scheduleNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  schedule Name
                </label>
                <span class="error-feedback" v-if="v$.newSchedule.name.$error">
                  {{ v$.newSchedule.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newSchedule.time.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="scheduleTimeFloat"
                  placeholder="John Smith"
                  v-model="newSchedule.time"
                />
                <label for="scheduleTimeFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  schedule Time
                </label>
                <span class="error-feedback" v-if="v$.newSchedule.time.$error">
                  {{ v$.newSchedule.time.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newSchedule.classroom.$error }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="scheduleClassroomFloat"
                  placeholder="John Smith"
                  v-model="newSchedule.classroom"
                />
                <label for="scheduleClassroomFloat">
                  <FIcons :icon="['fas', 'user']" />
                  schedule Classroom
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.newSchedule.classroom.$error"
                >
                  {{ v$.newSchedule.classroom.$errors[0].$message }}
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
    <!--Show Edit schedule Modal-->
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
              <label for="scheduleGroupFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Group
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentSchedule.group.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="GroupsList"
                  id="scheduleGroupFloat"
                  v-model="currentSchedule.group"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.group.$error"
                >
                  {{ v$.currentSchedule.group.$errors[0].$message }}
                </span>
              </div>
              <label for="scheduleTeacherFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Teacher
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentSchedule.teacher.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="TeachersList"
                  id="scheduleTeacherFloat"
                  v-model="currentSchedule.teacher"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.teacher.$error"
                >
                  {{ v$.currentSchedule.teacher.$errors[0].$message }}
                </span>
              </div>
              <label for="scheduleDisciplineFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Discipline
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentSchedule.discipline.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="DisciplinesList"
                  id="scheduleDisciplineFloat"
                  v-model="currentSchedule.discipline"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.discipline.$error"
                >
                  {{ v$.currentSchedule.discipline.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentSchedule.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="scheduleNameFloat"
                  placeholder="John Smith"
                  v-model="currentSchedule.name"
                />
                <label for="scheduleNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  schedule Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.name.$error"
                >
                  {{ v$.currentSchedule.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentSchedule.time.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="scheduleTimeFloat"
                  placeholder="John Smith"
                  v-model="currentSchedule.time"
                />
                <label for="scheduleTimeFloat">
                  <FIcons :icon="['far', 'envelope']" />
                  schedule Time
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.time.$error"
                >
                  {{ v$.currentSchedule.time.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentSchedule.classroom.$error,
                }"
              >
                <input
                  type="tel"
                  class="form-control"
                  id="scheduleClassroomFloat"
                  placeholder="John Smith"
                  v-model="currentSchedule.classroom"
                />
                <label for="scheduleClassroomFloat">
                  <FIcons :icon="['fas', 'user']" />
                  schedule Classroom
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentSchedule.classroom.$error"
                >
                  {{ v$.currentSchedule.classroom.$errors[0].$message }}
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
    <!--Show Delete schedule Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current schedule</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this schedule?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentSchedule.group
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentSchedule.teacher
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentSchedule.discipline
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentSchedule.name
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'envelope']" />{{
                    currentSchedule.time
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentSchedule.classroom
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
    <!--Show Delete All schedules Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All schedule</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all schedule?
              </h6>
              <p class="text-danger">
                Deleting all schedules will remove them from your system
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
  name: "RegisterUsers",
  components: {},
  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      scheduleData: [],
      GroupNameList: [],
      GroupsList: [],
      TeacherNameList: [],
      TeachersList: [],
      DisciplineNameList: [],
      DisciplinesList: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newSchedule: {
        group: "",
        teacher: "",
        discipline: "",
        name: "",
        time: "",
        classroom: "",
      },
      currentSchedule: {},
    };
  },
  validations() {
    return {
      newSchedule: {
        group: { required },
        teacher: { required },
        discipline: { required },
        name: { required, minLength: minLength(3) },
        time: { required },
        classroom: { required, minLength: minLength(1) },
      },
      currentSchedule: {
        group: { required },
        teacher: { required },
        discipline: { required },
        name: { required, minLength: minLength(3) },
        time: { required },
        classroom: { required, minLength: minLength(1) },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getScheduleData();
    this.getGroupsData();
    this.getTeachersData();
    this.getDisciplinesData();
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
    async getScheduleData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/schedule.php?action=read"
      );
      const restData = res.data;
      console.log("lala");
      console.log(restData.schedule);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.scheduleData = restData.schedule;
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
          this.TeacherNameList = restData.teachers;
          this.TeachersList = [];
          this.TeacherNameList.forEach((element) =>
            this.TeachersList.push(element.surname)
          );
        }
      }
    },
    async getDisciplinesData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/disciplines.php?action=read"
      );
      const restData = res.data;
      console.log(restData.disciplines);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.DisciplineNameList = restData.disciplines;
          this.DisciplinesList = [];
          this.DisciplineNameList.forEach((element) =>
            this.DisciplinesList.push(element.name)
          );
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/schedule.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.schedule);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.scheduleData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentSchedule);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/schedule.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.schedule);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newScheduleDate = this.scheduleData.filter(
            (element) => element.id != this.currentSchedule.id
          );
          console.table(newScheduleDate);
          this.successMsg = restData.message;
          this.currentSchedule = {};
          this.scheduleData = newScheduleDate;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/schedule.php"
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
      this.v$.newSchedule.$validate();
      if (!this.v$.newSchedule.$error) {
        this.addModalDialog(false);
        console.log("Add New User : Validated Successfully");
        let formData = this.toFormData(this.newSchedule);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/schedule.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newSchedule = {
              group: "",
              teacher: "",
              discipline: "",
              name: "",
              time: "",
              classroom: "",
            };
            this.successMsg = restData.message;
            this.getScheduleData();
            this.v$.newSchedule.$reset();
          }
        }
      } else {
        console.log("Add New User : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentSchedule.$validate();
      if (!this.v$.currentSchedule.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentSchedule);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/schedule.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.schedule);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentSchedule = {};
            this.successMsg = restData.message;
            this.getScheduleData();
            this.v$.currentSchedule.$reset();
          }
        }
        console.log("Update User : Validated Successfully");
      } else {
        console.log("Update User : Not Validated");
      }
    },
    selectSchedule(std) {
      this.currentSchedule = std;
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
