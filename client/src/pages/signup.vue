<template>
  <div>
    <h2 :class="$style.title">
      新規ユーザー登録
    </h2>
    <input type="text" placeholder="User Name" :class="$style.form">
    <input type="text" placeholder="Email" :class="$style.form">
    <input type="password" placeholder="Password" :class="$style.form">
    <button :class="$style.button">
      新規登録
    </button>
    <span :class="$style.border"><!-- border --></span>
    <NLink to="/signin" :class="[$style.button, $style.white]">
      既にアカウントを持っている方
    </NLink>
    <NLink to="/books" :class="[$style.button, $style.white]">
      アカウントを作成せずに始める
    </NLink>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'

export default Vue.extend({
  layout: 'sign'
})
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
  padding: 10px 15px;
  border: 1px solid #999;
  border-radius: 5px;
}

.form::placeholder {
  color: #999;
}

.form:first-of-type {
  margin-top: 20px;
}

.form + .form {
  margin-top: 10px;
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
