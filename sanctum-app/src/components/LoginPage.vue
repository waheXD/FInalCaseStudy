<template>
  <section id="login">
    <div class="wrapper">
      <div class="login-container">
        <div class="login-details">
          <h1 class="title">Hospital <span>Management</span> System</h1>
        </div>
        <form @submit.prevent="loginUser">
          <div class="field-con">
            <input type="email" class="form-control" name="email" id="email" v-model="email" placeholder="Email"
              @input="clearErrors" required />
            <small class="text-danger" v-if="errors">{{ errors }}</small>
          </div>
          <div class="field-con">
            <input type="password" class="form-control" name="password" placeholder="Password" v-model="password"
              @input="clearErrors" required />
          </div>
          <div class="btn-con">
            <button type="submit" class="btn-primary">LOGIN</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      email: "",
      password: "",
      errors: null,
    };
  },
  methods: {
    async loginUser() {
      try {
        const response = await axios.post(this.$store.state.apiUrl + "/login", {
          email: this.email,
          password: this.password,
        });

        if (response.status === 200) {
          localStorage.setItem("user_id", response.data.user_id);
          localStorage.setItem("token", response.data.token);
          localStorage.setItem(
            "account_type",
            JSON.stringify(response.data.account_type)
          );
          this.$router.push("/dashboard");
        }
      } catch (error) {
        this.errors = error.response.data.message;
      }
    },
    clearErrors() {
      this.errors = null;
    },
  },
};
</script>

<style scoped>
#login {
  height: 100vh;
  position: relative;
  margin: 0;
  padding: 0;
}

#login::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color:rgb(196, 223, 255);
  z-index: -2;
  
}

.title {
  max-width: fit-content;
  margin-left: 350px;
  margin-top: -100px;
  margin-bottom: 60px;
  color: #f28500;
}


#login .login-container form {
  max-width: 500px;
  width: 100%;
  padding: 20px;
  background-color: bisque;
  box-shadow: var(--global-shadow);
  margin-left: 559px;
}





@media screen and (min-width: 1024px) {
  #login .login-container {
    padding: 200px 0;
    text-align: left;
  }
}
</style>