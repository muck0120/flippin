<template>
  <div :class="$style.wrap">
    <h2 :class="$style.title">
      新しく問題を作成する
    </h2>
    <div :class="$style.group">
      <p :class="$style.heading">
        <span>問題文（必須）</span>
        <span
          :class="[
            $style.counter,
            { [$style.error]: false }
          ]"
        >13/2000</span>
      </p>
      <textarea
        placeholder="2000文字以内で入力してください"
        :class="[
          $style.form,
          { [$style.error]: false }
        ]"
      />
    </div>
    <button
      :class="[
        $style.button,
        $style.button__image
      ]"
    >
      画像を選択
    </button>
    <div
      v-for="n in 4"
      :key="n"
      :class="$style.select"
    >
      <div :class="$style.group">
        <p :class="$style.heading">
          <span>選択肢{{ n }}（必須）</span>
          <span
            :class="[
              $style.counter,
              { [$style.error]: false }
            ]"
          >13/2000</span>
        </p>
        <textarea
          placeholder="2000文字以内で入力してください"
          :class="[
            $style.form,
            $style.form__select,
            { [$style.error]: false }
          ]"
        />
      </div>
      <div :class="$style.correct">
        正解
      </div>
      <fa
        :icon="faTrash"
        :class="$style.delete"
      />
    </div>
    <button
      :class="[
        $style.button,
        $style.button__add
      ]"
    >
      選択肢を追加
      <fa
        :icon="faPlus"
        :class="$style.button__add__icon"
      />
    </button>
    <div :class="$style.group">
      <p :class="$style.heading">
        <span>解説文</span>
        <span
          :class="[
            $style.counter,
            { [$style.error]: false }
          ]"
        >13/2000</span>
      </p>
      <textarea
        placeholder="2000文字以内で入力してください"
        :class="[
          $style.form,
          { [$style.error]: false }
        ]"
      />
    </div>
    <button
      :class="[
        $style.button,
        $style.button__image
      ]"
    >
      画像を選択
    </button>
    <div :class="$style.buttons">
      <button
        :class="[
          $style.button,
          $style.green,
          { [$style.disabled]: false }
        ]"
      >
        新規作成
      </button>
      <button :class="$style.button">
        キャンセル
      </button>
    </div>
  </div>
</template>

<script>
import { faTrash, faPlus } from '@fortawesome/free-solid-svg-icons'

export default {
  computed: {
    faTrash () {
      return faTrash
    },
    faPlus () {
      return faPlus
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
  margin-top: 40px;
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
  height: 240px;
  margin-top: 5px;
  padding: 10px 15px;
  border: 1px solid #999;
  border-radius: 5px;
}

.form.form__select {
  height: 80px;
}

.form.error {
  border: 1px solid #f6416c;
}

.form::placeholder {
  color: #999;
}

.select > .group {
  margin-top: 0;
}

.select {
  margin-top: 40px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.select + .select {
  margin-top: 10px;
}

.correct {
  $height-pc: 50px;
  $height-tb: 45px;
  $height-sp: 40px;

  width: 80px;
  height: $height-pc;
  margin: 23px 0 0 30px;
  color: #fff;
  background-color: #999;
  border-radius: $height-pc / 2;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  white-space: nowrap;
  transition: all 0.3s;

  @include mq(tb) {
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
    margin: 21px 0 0 25px;
  }

  @include mq(sp) {
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 14px;
    margin: 19px 0 0 15px;
  }
}

.correct:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.delete {
  margin: 23px 0 0 30px;
  color: #999;
  font-size: 40px;
  cursor: pointer;
  transition: all 0.3s;

  @include mq(tb) {
    font-size: 35px;
    margin: 21px 0 0 25px;
  }

  @include mq(sp) {
    font-size: 30px;
    margin: 19px 0 0 15px;
  }
}

.delete:hover {
  opacity: 0.7;
  transition: all 0.3s;
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
    margin-top: 60px;
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
    width: 180px;
    height: $height-tb;
    border-radius: $height-tb / 2;
    font-size: 16px;
  }

  @include mq(sp) {
    width: 150px;
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

.button.green.disabled {
  background-color: #fff;
  color: #999;
  border: 3px solid #999;
  pointer-events: none;
}

.button.button__image {
  margin-top: 10px;
}

.button.button__add {
  margin-top: 10px;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}

.button__add__icon {
  margin-left: 10px;

  @include mq(sp) {
    margin-left: 5px;
  }
}
</style>
