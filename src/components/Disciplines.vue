<template>
  <h1 class="text-center">
    Disciplines <span class="smaller">KPI University</span>
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
    <!--Show Add Discipline Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        <FIcons :icon="['fas', 'user']" />
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="DisciplineData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Disciplines Details: Tables-->
    <div class="table-responsive">
      <table class="table table-border table-striped caption-top">
        <caption>
          List of Registered Disciplines ({{
            DisciplineData.length
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
              <FIcons :icon="['fas', 'cog']" />
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="text-center"
            v-for="(discipline, i) in DisciplineData"
            :key="i"
          >
            <td>{{ discipline.id }}</td>
            <td>{{ discipline.name }}</td>
            <td>
              <button
                class="btn btn-danger"
                @click="
                  deleteModalDialog(true);
                  selectDiscipline(discipline);
                "
              >
                <FIcons :icon="['fas', 'user-times']" />
                Delete
              </button>
              <button
                class="btn btn-warning"
                @click="
                  updateModalDialog(true);
                  selectDiscipline(discipline);
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
    <!--Show Add New Discipline Modal-->
    <div id="overlay" v-if="showAddModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info">Add New Discipline</h5>
            <button aria-hidden="true" @click="addModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newDiscipline.name.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="disciplineNameFloat"
                  placeholder="John Smith"
                  v-model="newDiscipline.name"
                />
                <label for="disciplineNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Discipline Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.newDiscipline.name.$error"
                >
                  {{ v$.newDiscipline.name.$errors[0].$message }}
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
    <!--Show Edit Discipline Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update Discipline</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentDiscipline.name.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="disciplineNameFloat"
                  placeholder="John Smith"
                  v-model="currentDiscipline.name"
                />
                <label for="disciplineNameFloat">
                  <FIcons :icon="['far', 'user']" />
                  Discipline Name
                </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentDiscipline.name.$error"
                >
                  {{ v$.currentDiscipline.name.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current Discipline
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Discipline Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Discipline</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this discipline?
              </h6>
              <p>
                <span
                  ><FIcons :icon="['fas', 'user']" />{{
                    currentDiscipline.name
                  }}</span
                ><br />
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current Discipline
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
    <!--Show Delete All Disciplines Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All Disciplines</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all disciplines?
              </h6>
              <p class="text-danger">
                Deleting all disciplines will remove them from your system
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteAllUsers()">
                  Delete All Disciplines
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
  name: "Disciplines-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      DisciplineData: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newDiscipline: {
        name: "",
      },
      currentDiscipline: {},
    };
  },
  validations() {
    return {
      newDiscipline: {
        name: { required, minLength: minLength(5) },
      },
      currentDiscipline: {
        name: { required, minLength: minLength(5) },
      },
    };
  },
  mounted() {
    this.dbConnection();
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
          this.DisciplineData = restData.disciplines;
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/disciplines.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.disciplines);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.DisciplineData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentDiscipline);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/disciplines.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.disciplines);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newDisciplinesData = this.DisciplineData.filter(
            (element) => element.id != this.currentDiscipline.id
          );
          console.table(newDisciplinesData);
          this.successMsg = restData.message;
          this.currentDiscipline = {};
          this.DisciplineData = newDisciplinesData;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/disciplines.php"
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
      this.v$.newDiscipline.$validate();
      if (!this.v$.newDiscipline.$error) {
        console.log("Add New Discipline : Validated Successfully");
        this.addModalDialog(false);
        let formData = this.toFormData(this.newDiscipline);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/disciplines.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newDiscipline = { name: "" };
            this.successMsg = restData.message;
            this.getDisciplinesData();
            this.v$.newDiscipline.$reset();
          }
        }
      } else {
        console.log("Add New Discipline : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentDiscipline.$validate();
      if (!this.v$.currentDiscipline.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentDiscipline);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/disciplines.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.disciplines);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentDiscipline = {};
            this.successMsg = restData.message;
            this.getDisciplinesData();
            this.v$.currentDiscipline.$reset();
          }
        }
        console.log("Update Discipline : Validated Successfully");
      } else {
        console.log("Update Discipline : Not Validated");
      }
    },
    selectDiscipline(std) {
      this.currentDiscipline = std;
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
