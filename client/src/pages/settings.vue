<template>
  <ValidationObserver
    v-slot="{ invalid }"
    tag="div"
    :class="$style.wrap"
  >
    <h2 :class="$style.title">
      設定
    </h2>
    <!-- User name field -->
    <ValidationProvider
      rules="required|max:20"
      v-slot="{ errors, failed }"
      tag="div"
      :class="[$style.group, $style.top]"
    >
      <p :class="$style.heading">
        <span>ユーザー名</span>
        <span
          :class="[
            $style.counter,
            { [$style.error]: failed }
          ]"
        >{{ name.length }}/20</span>
      </p>
      <input
        type="text"
        v-model="name"
        placeholder="20文字以内で入力してください"
        :class="[$style.form, { [$style.error_form]: failed }]"
      >
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <!-- User mail field -->
    <ValidationProvider
      rules="required|email"
      v-slot="{ errors, failed }"
      tag="div"
      :class="$style.group"
    >
      <p :class="$style.heading">
        メールアドレス
      </p>
      <input
        type="text"
        v-model="mail"
        placeholder="メールアドレス"
        :class="[$style.form, { [$style.error_form]: failed }]"
      >
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <!-- User password field -->
    <div :class="$style.group">
      <p :class="$style.heading">
        パスワード
      </p>
      <input
        type="password"
        v-model="password"
        placeholder="変更する場合のみ入力してください"
        :class="[$style.form, { [$style.error]: false }]"
      >
    </div>
    <div :class="$style.buttons">
      <button
        @click="update()"
        :class="[$style.button, $style.green]"
        :disabled="invalid"
      >
        変更する
      </button>
      <NLink
        to="/books"
        tag="button"
        :class="$style.button"
      >
        トップへ戻る
      </NLink>
    </div>
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
  SUCCESS: {
    TITLE: '更新しました',
    DESC: 'ユーザー情報の更新が完了しました。'
  },
  REJECT: {
    TITLE: '更新に失敗しました',
    DESC: 'このメールアドレスは既に使用されています。'
  },
  ERROR: {
    TITLE: '通信エラー',
    DESC: 'エラーが発生しました。もう一度お試し下さい。'
  }
}

export default {
  head () {
    return {
      title: 'ユーザー情報の更新'
    }
  },
  middleware: ['authenticated'],
  components: {
    ValidationObserver,
    ValidationProvider,
    Modal,
    ModalButtonOne
  },
  data () {
    return {
      password: '',
      isOpenModal: false,
      modalContent: null
    }
  },
  asyncData ({ store }) {
    return {
      name: store.state.user.user.user_name,
      mail: store.state.user.user.user_mail
    }
  },
  methods: {
    async update () {
      this.$nuxt.$loading.start()
      const status = await this.$store.dispatch('user/update', {
        name: this.name,
        mail: this.mail,
        password: this.password
      })
      switch (status) {
        case 200:
          this.password = ''
          this.modalContent = MODAL_CONTENT.SUCCESS
          break
        case 422:
          this.modalContent = MODAL_CONTENT.REJECT
          break
        default:
          this.modalContent = MODAL_CONTENT.ERROR
          break
      }
      this.isOpenModal = true
      this.$nuxt.$loading.finish()
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  padding: 30px 30px 60px;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;

  @include mq(tb) {
    padding: 30px 20px 60px;
  }

  @include mq(sp) {
    padding: 30px 15px 60px;
  }
}

.title {
  font-size: 24px;
  font-weight: bold;

  @include mq(tb) {
    font-size: 21px;
  }

  @include mq(sp) {
    font-size: 18px;
  }
}

.group {
  width: 100%;
  margin-top: 20px;
}

.group.top {
  margin-top: 30px;
}

.heading {
  font-size: 18px;
  font-weight: bold;
  display: flex;
  justify-content: flex-start;
  align-items: baseline;
  white-space: nowrap;

  @include mq(tb) {
    font-size: 16px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.counter {
  margin-left: auto;
  font-size: 14px;
  font-weight: normal;

  @include mq(tb) {
    font-size: 12px;
  }

  @include mq(sp) {
    font-size: 10px;
  }
}

.counter.error {
  color: #f6416c;
}

.form {
  width: 100%;
  margin-top: 5px;
  padding: 10px 15px;
  border: 1px solid #999;
  border-radius: 5px;
}

.form.error_form {
  border: 1px solid #f6416c;
  color: #f6416c;
}

.error_text {
  margin-top: 5px;
  color: #f6416c;
}

.form::placeholder {
  color: #999;
}

.buttons {
  margin-top: 100px;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(tb) {
    margin-top: 80px;
  }

  @include mq(sp) {
    margin-top: 30px;
  }
}

.button {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 200px;
  height: $height-pc;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(tb) {
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 13px;
  }
}

.button:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.button.green {
  background-color: #00b8a9;
  color: #fff;
}

.button.green:disabled {
  background-color: #fff;
  color: #999;
  border: 3px solid #999;
  pointer-events: none;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}
</style>
