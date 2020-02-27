<template>
  <div :class="$style.wrap">
    <NLink to="/books" tag="h1" :class="$style.title">
      Flippin
    </NLink>
    <nav :class="$style.nav">
      <template v-if="!isLoggedIn">
        <NLink to="/signup" :class="$style.button">
          ユーザー登録
        </NLink>
        <NLink to="/signin" :class="[$style.button, $style.green]">
          ログイン
        </NLink>
      </template>
      <template v-if="isLoggedIn">
        <NLink to="/settings" :class="$style.user">
          <fa :icon="faUser" :class="$style.user__icon" />
          <span :class="$style.user__name">デモユーザー</span>
        </NLink>
        <NLink to="/" :class="[$style.button, $style.green]">
          ログアウト
        </NLink>
      </template>
    </nav>
  </div>
</template>

<script>
import { faUser } from '@fortawesome/free-solid-svg-icons'

export default {
  data () {
    return {
      isLoggedIn: false
    }
  },
  computed: {
    faUser () {
      return faUser
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  background-color: #00b8a9;
  padding: 0 30px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  @include mq(tb) {
    padding: 0 20px;
  }

  @include mq(sp) {
    padding: 0 10px;
  }
}

.title {
  font-size: 30px;
  color: #fff;
  font-family: 'Baloo Bhai', cursive;

  @include mq(tb) {
    font-size: 28px;
  }

  @include mq(sp) {
    font-size: 26px;
  }
}

.title:hover {
  cursor: pointer;
}

.nav {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.button {
  $height-pc: 30px;
  $height-tb: 30px;
  $height-sp: 25px;

  display: flex;
  justify-content: center;
  align-items: center;
  width: 150px;
  height: $height-pc;
  border-radius: $height-pc / 2;
  background-color: #fff;
  transition: all 0.3s;

  @include mq(tb) {
    height: $height-tb;
    border-radius: $height-tb / 2;
    width: 120px;
  }

  @include mq(sp) {
    height: $height-sp;
    border-radius: $height-sp / 2;
    width: 100px;
  }
}

.button:hover {
  color: #fff;
  background-color: #00b8a9;
  border: 1px solid #fff;
  transition: all 0.3s;
}

.button.green {
  color: #fff;
  background-color: #00b8a9;
  border: 1px solid #fff;
  transition: all 0.3s;
}

.button.green:hover {
  color: #00b8a9;
  background-color: #fff;
  transition: all 0.3s;
}

.button + .button {
  margin-left: 20px;

  @include mq(tb) {
    margin-left: 10px;
  }

  @include mq(sp) {
    margin-left: 5px;
  }
}

.user {
  color: #fff;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 20px;
  transition: all 0.3s;

  @include mq(tb) {
    margin-right: 10px;
  }

  @include mq(sp) {
    margin-right: 10px;
  }
}

.user:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.user__icon {
  font-size: 20px;

  @include mq(tb) {
    font-size: 16px;
  }

  @include mq(sp) {
    font-size: 14px;
  }
}

.user__name {
  font-weight: bold;
  margin-left: 5px;
}
</style>
