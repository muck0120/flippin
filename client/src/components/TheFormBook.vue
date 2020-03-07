<template>
  <div :class="$style.wrap">
    <h2 :class="$style.title">
      新しく問題集を作成する
    </h2>
    <ValidationObserver v-slot="{ invalid }">
      <!-- Book Title field -->
      <ValidationProvider
        rules="required|max:50"
        v-slot="{ errors, failed }"
      >
        <div :class="$style.group">
          <p :class="$style.heading">
            <span>タイトル（必須）</span>
            <span :class="[
              $style.counter,
              { [$style.error]: failed }
            ]">{{ title.length }}/50</span>
          </p>
          <input
            type="text"
            :value="title"
            @input="$emit('update:title', $event.target.value)"
            placeholder="50文字以内で入力してください"
            :class="[$style.form, { [$style.error_form]: failed }]"
          >
          <p
            v-if="failed"
            :class="$style.error_text"
          >
            {{ errors[0] }}
          </p>
        </div>
      </ValidationProvider>
      <!-- Book Description field -->
      <ValidationProvider
        rules="max:200"
        v-slot="{ errors, failed }"
      >
        <div :class="$style.group">
          <p :class="$style.heading">
            <span>説明文</span>
            <span :class="[
              $style.counter,
              { [$style.error]: failed }
            ]">{{ desc.length }}/200</span>
          </p>
          <textarea
            :value="desc"
            @input="$emit('update:desc', $event.target.value)"
            placeholder="200文字以内で入力してください"
            :class="[$style.form, $style.textarea, { [$style.error_form]: failed }]"
          />
          <p
            v-if="failed"
            :class="$style.error_text"
          >
            {{ errors[0] }}
          </p>
        </div>
      </ValidationProvider>
      <label :class="$style.checklabel">
        <input
          type="checkbox"
          :value="isPublish"
          @input="$emit('update:isPublish', !isPublish)"
          :checked="isPublish"
          :class="$style.checkbox"
        >
        <span :class="$style.checktext">この問題集を公開する</span>
      </label>
      <div :class="$style.buttons">
        <button
          @click="$emit('submit')"
          :class="[$style.button, $style.green]"
          :disabled="invalid"
        >
          <template v-if="$route.path.match('create')">
            新規作成
          </template>
          <template v-if="$route.path.match('update')">
            更新する
          </template>
        </button>
        <button
          @click="$emit('cancel')"
          :class="$style.button"
        >
          キャンセル
        </button>
      </div>
    </ValidationObserver>
  </div>
</template>

<script>
import { ValidationObserver, ValidationProvider } from 'vee-validate'

export default {
  props: {
    title: {
      type: String,
      required: true
    },
    desc: {
      type: String,
      required: true
    },
    isPublish: {
      type: Boolean,
      required: true
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider
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

.form.textarea {
  height: 120px;
}

.checklabel {
  display: inline-block;
  margin-top: 30px;

  @include mq(tb) {
    margin-top: 25px;
  }

  @include mq(sp) {
    margin-top: 20px;
  }
}

.checktext {
  padding-left: 30px;
  font-size: 18px;
  font-weight: bold;
  position: relative;
  transition: all 0.3s;

  @include mq(tb) {
    padding-left: 27px;
    font-size: 16px;
  }

  @include mq(sp) {
    padding-left: 24px;
    font-size: 14px;
  }
}

.checktext::before {
  $square-pc: 25px;
  $square-tb: 22px;
  $square-sp: 19px;

  content: '';
  display: block;
  width: $square-pc;
  height: $square-pc;
  position: absolute;
  top: calc(50% - #{$square-pc} / 2);
  left: 0;
  background-image: url('~assets/images/check-off.svg');
  background-size: contain;
  background-position: center center;
  background-repeat: no-repeat;
  transition: all 0.3s;

  @include mq(tb) {
    width: $square-tb;
    height: $square-tb;
    top: calc(50% - #{$square-tb} / 2);
  }

  @include mq(sp) {
    width: $square-sp;
    height: $square-sp;
    top: calc(50% - #{$square-sp} / 2);
  }
}

.checkbox:checked + .checktext::before {
  background-image: url('~assets/images/check-on.svg');
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
