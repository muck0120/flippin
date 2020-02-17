<template>
  <div :class="$style.wrap">
    <h2 :class="$style.title">
      設定
    </h2>
    <div :class="$style.group">
      <p :class="$style.heading">
        <span>ユーザー名</span>
        <span :class="$style.counter">13/20</span>
      </p>
      <input
        type="text"
        placeholder="20文字以内で入力してください"
        :class="$style.form"
      >
    </div>
    <div :class="$style.group">
      <p :class="$style.heading">
        メールアドレス
      </p>
      <input
        type="text"
        placeholder="メールアドレス"
        :class="$style.form"
      >
    </div>
    <div :class="$style.group">
      <p :class="$style.heading">
        パスワード
      </p>
      <input
        type="text"
        placeholder="パスワード"
        :class="$style.form"
      >
    </div>
    <div :class="$style.buttons">
      <button
        :class="[
          $style.button,
          $style.green,
          { [$style.disabled]: false }
        ]"
      >
        変更する
      </button>
      <button :class="$style.button">
        キャンセル
      </button>
    </div>
  </div>
</template>

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
    font-size: 22px;
  }

  @include mq(sp) {
    font-size: 20px;
  }
}

.group {
  width: 100%;
}

.group:first-of-type {
  margin-top: 30px;
}

.group + .group {
  margin-top: 20px;
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

.form {
  width: 100%;
  margin-top: 5px;
  padding: 10px 15px;
  border: 1px solid #999;
  border-radius: 5px;
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
  $height: 50px;
  $height-sp: 40px;

  width: 200px;
  height: $height;
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: $height / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #00b8a9;
  font-size: 18px;
  font-weight: bold;
  transition: all 0.3s;
  white-space: nowrap;

  @include mq(sp) {
    font-size: 16px;
  }

  @include mq(sp) {
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 14px;
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

.button.green.disabled {
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
