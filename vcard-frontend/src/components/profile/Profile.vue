<template>
  <div class="container">
    <body v-if="isUserVisible">
        <section class="section about-section gray-bg" id="about">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-lg-6">
                        <div class="about-text go-to">
                            <h3 class="dark-color"> {{ user.name }}</h3>
                            <h6 class="theme-color lead">Email: {{ user.email }}</h6>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-avatar text-center">
                            <img class="w-50 rounded-circle" :src="this.baseURL + user.photo_url" :title="currentUser.username+'Photo'" alt="">
                        </div>
                    </div>
                </div>
                <div class="counter mt-3">
                    <div v-if="user.user_type == 'V'" class="row">
                        <div class="col-6 col-lg-4">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="500" data-speed="500">{{ user.balance }} €</h6>
                                <p class="m-0px font-w-600">Balance</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="150" data-speed="150">{{ user.max_debit }} €</h6>
                                <p class="m-0px font-w-600">Max Debit</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-4">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="850" data-speed="850">{{ user.count_transactions }}</h6>
                                <p class="m-0px font-w-600">Total Transactions</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="row">
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="500" data-speed="500">{{ user.count_today_transactions }}</h6>
                                <p class="m-0px font-w-600">Transactions Today</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="150" data-speed="150">{{ user.count_month_transactions }}</h6>
                                <p class="m-0px font-w-600">Transactions Last Month</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="850" data-speed="850">{{ user.count_transactions }}</h6>
                                <p class="m-0px font-w-600">Total Transactions</p>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="count-data text-center">
                                <h6 class="count h2" data-to="850" data-speed="850">{{ user.count_vcards }}</h6>
                                <p class="m-0px font-w-600">Total vCards</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </body>
  </div>
</template>

<script>
import UserService from "../../services/user.service";

export default {
  name: "Profile",
  data() {
    return {
      user: {},
      isUserVisible: false,
    };
  },
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    },
  },
  async mounted() {
    if (!this.currentUser) {
      this.$router.push("/login");
    } else {
      if (this.currentUser.username) {
        this.user = await UserService.getUser(this.currentUser.id);
        this.user = this.user.data.data;
        this.isUserVisible = true;
      }
    }
  },
};
</script>

<style scoped>body{
    color: #6F8BA4;
    margin-top:20px;
}
.section {
    padding: 100px 0;
    position: relative;
}
.gray-bg {
    background-color: #f5f5f5;
}
img {
    max-width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
/* About Me 
---------------------*/
.about-text h3 {
  font-size: 45px;
  font-weight: 700;
  margin: 0 0 6px;
}
@media (max-width: 767px) {
  .about-text h3 {
    font-size: 35px;
  }
}
.about-text h6 {
  font-weight: 600;
  margin-bottom: 15px;
}
@media (max-width: 767px) {
  .about-text h6 {
    font-size: 18px;
  }
}
.about-text p {
  font-size: 18px;
  max-width: 450px;
}
.about-text p mark {
  font-weight: 600;
  color: #20247b;
}

.about-list {
  padding-top: 10px;
}
.about-list .media {
  padding: 5px 0;
}
.about-list label {
  color: #20247b;
  font-weight: 600;
  width: 88px;
  margin: 0;
  position: relative;
}
.about-list label:after {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  right: 11px;
  width: 1px;
  height: 12px;
  background: #20247b;
  -moz-transform: rotate(15deg);
  -o-transform: rotate(15deg);
  -ms-transform: rotate(15deg);
  -webkit-transform: rotate(15deg);
  transform: rotate(15deg);
  margin: auto;
  opacity: 0.5;
}
.about-list p {
  margin: 0;
  font-size: 15px;
}

@media (max-width: 991px) {
  .about-avatar {
    margin-top: 30px;
  }
}

.about-section .counter {
  padding: 22px 20px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
}
.about-section .counter .count-data {
  margin-top: 10px;
  margin-bottom: 10px;
}
.about-section .counter .count {
  font-weight: 700;
  color: #20247b;
  margin: 0 0 5px;
}
.about-section .counter p {
  font-weight: 600;
  margin: 0;
}
mark {
    background-image: linear-gradient(rgba(252, 83, 86, 0.6), rgba(252, 83, 86, 0.6));
    background-size: 100% 3px;
    background-repeat: no-repeat;
    background-position: 0 bottom;
    background-color: transparent;
    padding: 0;
    color: currentColor;
}
.theme-color {
    color: #fc5356;
}
.dark-color {
    color: #20247b;
}

</style>