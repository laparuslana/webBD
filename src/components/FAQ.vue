<template>
  <h1 class="text-center">FAQ <span class="smaller">KPI University</span></h1>
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
    <!--Show Add Faq Button-->
    <div class="col-lg-12">
      <button class="float-end btn btn-info" @click="addModalDialog(true)">
        Add New
      </button>
      <button
        class="float-start btn btn-danger"
        v-if="FAQData.length > 0"
        @click="deleteAllModalDialog(true)"
      >
        <FIcons :icon="['fas', 'user-times']" />
        Delete All
      </button>
      <div class="clearfix"></div>
    </div>
    <hr class="bg-info" />
    <!--Show Faq Details: Tables-->
    <details v-for="(faq, i) in FAQData" :key="i">
      <summary>
        <div class="table-responsive">
          <table class="table table-border table-striped caption-top">
            <tbody>
              <tr class="text-center">
                <td class="first">{{ faq.id }}</td>
                <td class="second">{{ faq.question }}</td>
                <td class="third">
                  <button
                    class="btn btn-danger"
                    @click="
                      deleteModalDialog(true);
                      selectFaq(faq);
                    "
                  >
                    <FIcons :icon="['fas', 'user-times']" />
                    Delete
                  </button>
                  <button
                    class="btn btn-warning"
                    @click="
                      updateModalDialog(true);
                      selectFaq(faq);
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
      </summary>
      <div class="answer">{{ faq.answer }}</div>
    </details>
    <!--    <div class="table-responsive">-->
    <!--      <table class="table table-border table-striped caption-top">-->
    <!--        <caption>-->
    <!--          List of Registered Faq ({{-->
    <!--            FAQData.length-->
    <!--          }})-->
    <!--        </caption>-->
    <!--        <thead>-->
    <!--          <tr class="bg-success text-light text-center">-->
    <!--            <th>-->
    <!--              <FIcons :icon="['fas', 'id-badge']" />-->
    <!--              ID-->
    <!--            </th>-->
    <!--            <th>Question</th>-->
    <!--            <th>Answer</th>-->
    <!--            <th>-->
    <!--              <FIcons :icon="['fas', 'cog']" />-->
    <!--              Actions-->
    <!--            </th>-->
    <!--          </tr>-->
    <!--        </thead>-->
    <!--        <tbody>-->
    <!--          <tr class="text-center" v-for="(faq, i) in FAQData" :key="i">-->
    <!--            <td>{{ faq.id }}</td>-->
    <!--            <td>{{ faq.question }}</td>-->
    <!--            <td>{{ faq.answer }}</td>-->
    <!--            <td>-->
    <!--              <button-->
    <!--                class="btn btn-danger"-->
    <!--                @click="-->
    <!--                  deleteModalDialog(true);-->
    <!--                  selectFaq(faq);-->
    <!--                "-->
    <!--              >-->
    <!--                <FIcons :icon="['fas', 'user-times']" />-->
    <!--                Delete-->
    <!--              </button>-->
    <!--              <button-->
    <!--                class="btn btn-warning"-->
    <!--                @click="-->
    <!--                  updateModalDialog(true);-->
    <!--                  selectFaq(faq);-->
    <!--                "-->
    <!--              >-->
    <!--                <FIcons :icon="['fas', 'user-edit']" />-->
    <!--                Update-->
    <!--              </button>-->
    <!--            </td>-->
    <!--          </tr>-->
    <!--        </tbody>-->
    <!--      </table>-->
    <!--    </div>-->
    <!--Show Add New Faq Modal-->
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
                :class="{ 'form-group-error': v$.newFaq.question.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="faqQuestionFloat"
                  placeholder="John Smith"
                  v-model="newFaq.question"
                />
                <label for="faqQuestionFloat"> Faq question </label>
                <span class="error-feedback" v-if="v$.newFaq.question.$error">
                  {{ v$.newFaq.question.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.newFaq.answer.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="faqAnswerFloat"
                  placeholder="John Smith"
                  v-model="newFaq.answer"
                />
                <label for="faqAnswerFloat"> Answer </label>
                <span class="error-feedback" v-if="v$.newFaq.answer.$error">
                  {{ v$.newFaq.answer.$errors[0].$message }}
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
    <!--Show Edit Faq Modal-->
    <div id="overlay" v-if="showUpdateModal">
      <!--      <div class="modal">-->
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning">Update Faq</h5>
            <button aria-hidden="true" @click="updateModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <div
                class="form-floating mb-3"
                :class="{ 'form-group-error': v$.currentFaq.question.$error }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="faqQuestionFloat"
                  placeholder="John Smith"
                  v-model="currentFaq.question"
                />
                <label for="faqQuestionFloat"> Faq question </label>
                <span
                  class="error-feedback"
                  v-if="v$.currentFaq.question.$error"
                >
                  {{ v$.currentFaq.question.$errors[0].$message }}
                </span>
              </div>
              <div
                class="form-floating mb-3"
                :class="{
                  'form-group-error': v$.currentFaq.answer.$error,
                }"
              >
                <input
                  type="text"
                  class="form-control"
                  id="faqAnswerFloat"
                  placeholder="John Smith"
                  v-model="currentFaq.answer"
                />
                <label for="faqAnswerFloat"> Answer </label>
                <span class="error-feedback" v-if="v$.currentFaq.answer.$error">
                  {{ v$.currentFaq.answer.$errors[0].$message }}
                </span>
              </div>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-warning" @click="updateUser()">
                  Update Current Faq
                </button>
              </div>
            </form>
          </div>
          <!--          </div>-->
        </div>
      </div>
    </div>
    <!--Show Delete Faq Modal-->
    <div id="overlay" v-if="showDeleteModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delet Current Faq</h5>
            <button aria-hidden="true" @click="deleteModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete this Faq?
              </h6>
              <p>
                <span>{{ currentFaq.question }}</span
                ><br />
                <span>{{ currentFaq.answer }}</span
                ><br />
              </p>
              <hr class="bg-info" />
              <div class="d-grid gap-2">
                <button class="btn btn-danger" @click="deleteUser()">
                  Delete Current Faq
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
    <!--Show Delete All Faq Modal-->
    <div id="overlay" v-if="showDeleteAllModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger">Delete All Faq</h5>
            <button aria-hidden="true" @click="deleteAllModalDialog(false)">
              <FIcons :icon="['fas', 'times']" />
            </button>
          </div>
          <div class="modal-body p-4">
            <form action="" method="post" @click.prevent>
              <h6 class="text-danger">
                Are you sure you want to delete all Faq?
              </h6>
              <p class="text-danger">
                Deleting all Faq will remove them from your system
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
  name: "Faq-Page",
  components: {},

  data() {
    return {
      v$: useValidate(),
      errMsg: "",
      successMsg: "",
      isDBConnected: "",
      isDBConnectedMsg: "",
      FAQData: [],
      showAddModal: false,
      showUpdateModal: false,
      showDeleteModal: false,
      showDeleteAllModal: false,
      newFaq: {
        question: "",
        answer: "",
      },
      currentFaq: {},
    };
  },
  validations() {
    return {
      newFaq: {
        question: { required, minLength: minLength(3) },
        answer: { required, minLength: minLength(3) },
      },
      currentFaq: {
        question: { required, minLength: minLength(3) },
        answer: { required, minLength: minLength(3) },
      },
    };
  },
  mounted() {
    this.dbConnection();
    this.getFaqData();
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
    async getFaqData() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faq.php?action=read"
      );
      const restData = res.data;
      console.log(restData.faq);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.FAQData = restData.faq;
        }
      }
    },
    async deleteAllUsers() {
      this.deleteAllModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faq.php?action=deleteAll"
      );
      const restData = res.data;
      console.log(restData.faq);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          this.successMsg = restData.message;
          this.FAQData = "";
        }
      }
    },
    async deleteUser() {
      let formData = this.toFormData(this.currentFaq);
      this.deleteModalDialog(false);
      this.clearOldMsgs();
      let res = await axios.post(
        "http://localhost/webprojecttutorial/src/api/faq.php?action=delete",
        formData
      );
      const restData = res.data;
      console.log(restData.faq);
      if (res.status == 200) {
        if (restData.error) {
          //if error
          this.errMsg = restData.message;
        } else {
          //if no error
          let newFaqData = this.FAQData.filter(
            (element) => element.id != this.currentFaq.id
          );
          console.table(newFaqData);
          this.successMsg = restData.message;
          this.currentFaq = {};
          this.FAQData = newFaqData;
        }
      }
    },
    clearOldMsgs() {
      this.errMsg = "";
      this.successMsg = "";
    },
    async dbConnection() {
      let res = await axios.get(
        "http://localhost/webprojecttutorial/src/api/faq.php"
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
      this.v$.newFaq.$validate();
      if (!this.v$.newFaq.$error) {
        this.addModalDialog(false);
        console.log("Add New Faq : Validated Successfully");
        let formData = this.toFormData(this.newFaq);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/faq.php?action=create",
          formData
        );
        const restData = res.data;
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.newFaq = { question: "", answer: "" };
            this.successMsg = restData.message;
            this.getFaqData();
            this.v$.newFaq.$reset();
          }
        }
      } else {
        console.log("Add New Faq : Not Validated");
      }
    },
    async updateUser() {
      this.v$.currentFaq.$validate();
      if (!this.v$.currentFaq.$error) {
        this.updateModalDialog(false);
        this.clearOldMsgs();
        let formData = this.toFormData(this.currentFaq);
        let res = await axios.post(
          "http://localhost/webprojecttutorial/src/api/faq.php?action=update",
          formData
        );
        const restData = res.data;
        console.log(restData.faq);
        if (res.status == 200) {
          if (restData.error) {
            //if error
            this.errMsg = restData.message;
          } else {
            //if no error
            this.currentFaq = {};
            this.successMsg = restData.message;
            this.getFaqData();
            this.v$.currentFaq.$reset();
          }
        }
        console.log("Update Faq : Validated Successfully");
      } else {
        console.log("Update Faq : Not Validated");
      }
    },
    selectFaq(std) {
      this.currentFaq = std;
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

.first {
  width: 10%;
}

.second {
  width: 65%;
}
.third {
  text-align: right;
}

details summary {
  display: inline-block;
  width: 90%;
}

.answer {
  margin-left: 60px;
  padding-bottom: 50px;
}
</style>
