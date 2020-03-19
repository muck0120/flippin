<template>
  <ValidationObserver
    v-slot="{ invalid }"
    tag="div"
  >
    <h2 :class="$style.title">
      ログイン
    </h2>
    <!-- User mail field -->
    <ValidationProvider
      rules="required|email"
      v-slot="{ errors, failed }"
    >
      <input
        type="text"
        v-model="mail"
        placeholder="Email"
        :class="[$style.form, $style.top, { [$style.error_form]: failed }]"
      >
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <!-- User password field -->
    <ValidationProvider
      rules="required"
      v-slot="{ errors, failed }"
    >
      <input
        type="password"
        v-model="password"
        placeholder="Password"
        :class="[$style.form, { [$style.error_form]: failed }]"
      >
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <button
      :class="$style.button"
      :disabled="invalid"
      @click="login()"
    >
      ログイン
    </button>
    <span :class="$style.border"><!-- border --></span>
    <NLink
      :to="$route.query.redirect ? `/signup?redirect=${$route.query.redirect}` : `/signup`"
      :class="[$style.button, $style.white]"
    >
      アカウントを持っていない方
    </NLink>
    <NLink
      to="/books"
      :class="[$style.button, $style.white]"
    >
      ログインせずに始める
    </NLink>
    <Modal
      v-if="isOpenModal"
      @close="isOpenModal = false"
    >
      <template #title>
        {{ modalContent.TITLE }}
      </template>
      <template #desc>
        {{ modalContent.DESC }}
      </template>
      <template #button>
        <ModalButtonOne @proceed="isOpenModal = false" />
      </template>
    </Modal>
  </ValidationObserver>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import Modal from '@/components/Modal'
import ModalButtonOne from '@/components/ModalButtonOne'

const MODAL_CONTENT = {
  REJECT: {
    TITLE: 'ログイン失敗',
    DESC: 'メールアドレスとパスワードが一致しません。'
  },
  ERROR: {
    TITLE: '通信エラー',
    DESC: 'エラーが発生しました。もう一度お試し下さい。'
  }
}

export default {
  head () {
    return {
      title: 'ログイン'
    }
  },
  layout: 'sign',
  components: {
    ValidationObserver,
    ValidationProvider,
    Modal,
    ModalButtonOne
  },
  data () {
    return {
      mail: '',
      password: '',
      isOpenModal: false,
      modalContent: null
    }
  },
  methods: {
    async login () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch('user/login', {
        mail: this.mail,
        password: this.password
      })
      switch (status) {
        case 200:
          if (this.$route.query.redirect) {
            this.$router.push(this.$route.query.redirect)
          } else {
            this.$router.push('/books')
          }
          break
        case 401:
          this.modalContent = MODAL_CONTENT.REJECT
          this.isOpenModal = true
          break
        default:
          this.modalContent = MODAL_CONTENT.ERROR
          this.isOpenModal = true
          break
      }
      this.$nuxt.$loading.finish()
    }
  }
}
</script>

<style lang="scss" module>
.title {
  font-size: 20px;
  font-weight: bold;
  text-align: center;
}

.form {
  width: 100%;
  height: 40px;
  margin-top: 10px;
  padding: 10px 15px;
  border: 1px solid #999;
  border-radius: 5px;
}

.form::placeholder {
  color: #999;
}

.form.top {
  margin-top: 20px;
}

.form.error_form {
  border: 1px solid #f6416c;
  color: #f6416c;
}

.error_text {
  margin-top: 5px;
  color: #f6416c;
}

.button {
  $height: 40px;

  width: 100%;
  height: $height;
  background-color: #00b8a9;
  border-radius: $height / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #fff;
  margin-top: 10px;
  font-size: 18px;
  font-weight: bold;
  position: relative;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(sp) {
    font-size: 16px;
  }
}

.button::after {
  $square-pc: 20px;
  $square-sp: 16px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 20px;
  background-image: url('~assets/images/arrow-white.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center right;
  transition: all 0.3s;

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 15px;
  }
}

.button:hover::after {
  right: 15px;
  transition: all 0.3s;
}

.button:disabled {
  pointer-events: none;
  background-color: #999;
}

.button.white {
  font-size: 14px;
  background-color: #fff;
  border: 2px solid #00b8a9;
  color: #00b8a9;

  @include mq(sp) {
    font-size: 13px;
  }
}

.button.white::after {
  $square-pc: 16px;
  $square-sp: 14px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  right: 20px;
  background-image: url('~assets/images/arrow-green.svg');
  background-repeat: no-repeat;
  background-size: contain;
  background-position: center right;
  transition: all 0.3s;

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
    right: 15px;
  }
}

.button.white:hover::after {
  right: 15px;
  transition: all 0.3s;
}

.border {
  display: block;
  height: 3px;
  margin: 30px 0 20px;
  position: relative;
}

.border::before {
  content: '';
  background-image: linear-gradient(to right, #00b8a9, #00b8a9 5px, transparent 5px, transparent 8px);
  background-size: 8px 3px;
  background-repeat: repeat-x;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
}
</style>
