<template>
  <h1 class="text-center">
    Groups <span class="smaller">KPI University</span>
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
    <!--Show Add Group Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="GroupData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Groups Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Groups ({{
            GroupData.length
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
              Department
            </th>
            <th>
              <FIcons :icon="['fas', 'user']" />
              Name
            </th>
            <th>
              <FIcons :icon="['far', 'user']" />
              Course
            </th>
            <th>
              <FIcons :icon="['fas', 'cog']" />
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr class="text-center" v-for="(group, i) in GroupData" :key="i">
            <td>{{ group.id }}</td>
            <td>{{ group.departament }}</td>
            <td>{{ group.name }}</td>
            <td>{{ group.course }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectGroup(group);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectGroup(group);
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
    <!--Show Add New Group Modal-->
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
              <label for="groupDepartamentFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Department
              </label>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newGroup.departament.$error }"
              >
                <v-select
                  class="v-select"
                  :options="DepartamentsList"
                  id="groupDepartamentFloat"
                  v-model="newGroup.departament"
                />
                <span
                  class="error-feedback"
                  v-if="v$.newGroup.departament.$error"
                >
                  {{ v$.newGroup.departament.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newGroup.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="groupNameFloat"
                  placeholder="John Smith"
                  v-model="newGroup.name"
                />
                <label for="groupNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Group Name
                </label>
                <span class="error-feedback" v-if="v$.newGroup.name.$error">
                  {{ v$.newGroup.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.newGroup.course.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="groupCourseFloat"
                  placeholder="John Smith"
                  v-model="newGroup.course"
                />
                <label for="groupCourseFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Course
                </label>
                <span class="error-feedback" v-if="v$.newGroup.course.$error">
                  {{ v$.newGroup.course.$errors[0].$message }}
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
    <!--Show Edit Group Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update Group</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <!--v-select-->
              <label for="groupDepartamentFloat" class="label-for-vselect">
                <FIcons :icon="['far', 'user']" />
                Departament
              </label>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentGroup.departament.$error,
                }"
              >
                <v-select
                  class="v-select"
                  :options="DepartamentsList"
                  id="groupDepartamentFloat"
                  v-model="currentGroup.departament"
                />
                <span
                  class="error-feedback"
                  v-if="v$.currentGroup.departament.$error"
                >
                  {{ v$.currentGroup.departament.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentGroup.name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="groupNameFloat"
                  placeholder="John Smith"
                  v-model="currentGroup.name"
                />
                <label for="groupNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Group Name
                </label>
                <span class="error-feedback" v-if="v$.currentGroup.name.$error">
                  {{ v$.currentGroup.name.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentGroup.course.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="groupCourseFloat"
                  placeholder="John Smith"
                  v-model="currentGroup.course"
                />
                <label for="groupCourseFloat">
                  <FIcons :icon="['fas', 'user']" />
                  Course
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentGroup.course.$error"
                >
                  {{ v$.currentGroup.course.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current Group
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Group Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Group</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this group?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentGroup.departament
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentGroup.name
                  }}</span
                ><br />
                <span
                  ><FIcons :icon="['far', 'user']" />{{
                    currentGroup.course
                  }}</span
                ><br />
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current Group
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
    <!--Show Delete All Groups Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All Groups</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all groups?
              </h6>
              <p class="text-danger">
                Deleting all groups will remove them from your system
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
  name: "Groups-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      GroupData: [],
      DepartamentNameList: [],
      DepartamentsList: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newGroup: {
        departament: "",
        name: "",
        course: "",
      },
      currentGroup: {},
    };
  },
  validations() {
    return {
      newGroup: {
        name: { required, minLength: minLength(4) },
        course: { required, minLength: minLength(1) },
        departament: { required },
      },
      currentGroup: {
        name: { required, minLength: minLength(4) },
        course: { required, minLength: minLength(1) },
        departament: { required },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getGroupsData();
    this.getDepartamentsData();
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
          this.GroupData = restData.groups;
        }
      }
    },
    async getDepartamentsData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/departments.php?action=read"
      );
      const restData = res.data;
      console.log(restData.departaments);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.DepartamentNameList = restData.departaments;
          this.DepartamentsList = [];
          this.DepartamentNameList.forEach((element) =>
            this.DepartamentsList.push(element.short_name)
          );
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/groups.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.groups);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.GroupData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentGroup);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/groups.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.groups);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newGroupsData = this.GroupData.filter(
            (element) => element.id != this.currentGroup.id
          );
          console.table(newGroupsData);
          this.successMsg = restData.message;
          this.currentGroup = {};
          this.GroupData = newGroupsData;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/groups.php"
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
      this.v$.newGroup.$validate();
      if (!this.v$.newGroup.$error) {
        this.addModalDialog(false);
        console.log("Add New Group : Validated Successfully");
        let formData = this.toFormData(this.newGroup);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/groups.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newGroup = { departament: "", name: "", course: "" };
            this.successMsg = restData.message;
            this.getGroupsData();
            this.v$.newGroup.$reset();
          }
        }
      } else {
        console.log("Add New Group : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentGroup.$validate();
      if (!this.v$.currentGroup.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentGroup);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/groups.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.groups);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentGroup = {};
            this.successMsg = restData.message;
            this.getGroupsData();
            this.v$.currentGroup.$reset();
          }
        }
        console.log("Update Group : Validated Successfully");
      } else {
        console.log("Update Group : Not Validated");
      }
    },
    selectGroup(gr) {
      this.currentGroup = gr;
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
