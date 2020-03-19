<template>
  <ValidationObserver
    v-slot="{ invalid }"
    tag="div"
    ref="allValidateObserver"
    :class="$style.wrap"
  >
    <h2 :class="$style.title">
      新しく問題を作成する
    </h2>
    <!-- Card Question field -->
    <ValidationProvider
      rules="required|max:2000"
      v-slot="{ errors, failed }"
      tag="div"
      :class="$style.group"
    >
      <p :class="$style.heading">
        <span>問題文（必須）</span>
        <span :class="[
          $style.counter,
          { [$style.error]: failed }
        ]">{{ question.length }}/2000</span>
      </p>
      <textarea
        :value="question"
        @input="$emit('update:question', $event.target.value)"
        placeholder="2000文字以内で入力してください"
        :class="[$style.form, { [$style.error_form]: failed }]"
      />
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <!-- Card Question Image field -->
    <ValidationProvider
      rules="image"
      ref="questionimageValidateProvider"
      v-slot="{ validate, errors, failed }"
    >
      <img
        v-if="questionImage"
        :src="questionImage"
        :class="$style.image"
      >
      <label
        v-if="!questionImage"
        :class="[
          $style.button,
          $style.button_image,
          { [$style.button_red]: failed }
        ]"
      >
        <input
          type="file"
          @change="setQuestionImage($event)"
        >
        画像を選択
      </label>
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
      <button
        v-if="questionImage"
        @click="deleteQuestionImageUpload()"
        :class="[
          $style.button,
          $style.button_image,
          $style.button_red
        ]"
      >
        画像を削除
      </button>
    </ValidationProvider>
    <!-- Card Choices field -->
    <ValidationProvider
      v-for="(choice, index) in choices"
      :key="index"
      rules="required|max:200"
      v-slot="{ errors, failed }"
      tag="div"
      :class="$style.select"
    >
      <div :class="$style.select_inner">
        <div :class="$style.group">
          <p :class="$style.heading">
            <span>選択肢{{ index + 1 }}（必須）</span>
            <span :class="[
              $style.counter,
              { [$style.error]: failed }
            ]">{{ choice.card_choice_text.length }}/200</span>
          </p>
          <textarea
            :value="choice.card_choice_text"
            @input="setChoiceText($event.target.value, index)"
            placeholder="2000文字以内で入力してください"
            :class="[
              $style.form,
              $style.form_select,
              { [$style.error_form]: failed }
            ]"
          />
        </div>
        <input
          type="radio"
          name="correct"
          @input="setChoiceIsCorrect(index)"
          :checked="choice.card_choice_is_correct"
          :id="`correct${index}`"
          :class="$style.radio"
        />
        <label
          :for="`correct${index}`"
          :class="$style.correct"
        >
          正解
        </label>
        <div :class="$style.delete">
          <div
            v-if="index > 1"
            @click="deleteChoice(index)"
            :class="$style.delete_inner"
          >
            <fa :icon="faTrash" />
          </div>
        </div>
      </div>
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <button
      @click="$emit('addChoice')"
      :class="[$style.button, $style.button_add]"
    >
      選択肢を追加
      <fa
        :icon="faPlus"
        :class="$style.button_add_icon"
      />
    </button>
    <!-- Card Explanation field -->
    <ValidationProvider
      rules="max:2000"
      v-slot="{ errors, failed }"
      tag="div"
      :class="$style.group"
    >
      <p :class="$style.heading">
        <span>解説文</span>
        <span :class="[
          $style.counter,
          { [$style.error]: failed }
        ]">{{ explanation.length }}/2000</span>
      </p>
      <textarea
        :value="explanation"
        @input="$emit('update:explanation', $event.target.value)"
        placeholder="2000文字以内で入力してください"
        :class="[$style.form, { [$style.error_form]: failed }]"
      />
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
    </ValidationProvider>
    <!-- Card Explanation Image field -->
    <ValidationProvider
      rules="image"
      ref="explanationImageValidateProvider"
      :disabled="true"
      v-slot="{ validate, errors, failed }"
    >
      <img
        v-if="explanationImage"
        :src="explanationImage"
        :class="$style.image"
      >
      <label
        v-if="!explanationImage"
        :class="[
          $style.button,
          $style.button_image,
          { [$style.button_red]: failed }
        ]"
      >
        <input
          type="file"
          @change="setExplanationImage($event)"
        >
        画像を選択
      </label>
      <p
        v-if="failed"
        :class="$style.error_text"
      >
        {{ errors[0] }}
      </p>
      <button
        v-if="explanationImage"
        @click="deleteExplanationImageUpload()"
        :class="[
          $style.button,
          $style.button_image,
          $style.button_red
        ]"
      >
        画像を削除
      </button>
    </ValidationProvider>
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
</template>

<script>
import clonedeep from 'lodash.clonedeep'
import { ValidationObserver, ValidationProvider } from 'vee-validate'
import { faTrash, faPlus } from '@fortawesome/free-solid-svg-icons'

export default {
  props: {
    question: {
      type: String,
      required: true
    },
    questionImage: {
      type: String,
      required: true
    },
    choices: {
      type: Array,
      required: true
    },
    explanation: {
      type: String,
      required: true
    },
    explanationImage: {
      type: String,
      required: true
    }
  },
  components: {
    ValidationObserver,
    ValidationProvider
  },
  computed: {
    faTrash () {
      return faTrash
    },
    faPlus () {
      return faPlus
    }
  },
  methods: {
    setChoiceText (text, index) {
      const choices = clonedeep(this.choices)
      choices[index].card_choice_text = text
      this.$emit('update:choices', choices)
    },
    setChoiceIsCorrect (correctIndex) {
      const choices = clonedeep(this.choices)
      choices.forEach((choice, index) => {
        choice.card_choice_is_correct =
          index === correctIndex ? true : false
      })
      this.$emit('update:choices', choices)
    },
    async setQuestionImage (e) {
      const { valid } =
        await this.$refs.questionimageValidateProvider.validate(e)
      if (!valid || !e.target.files[0]) return false
      this.$emit('questionImageUpload', { file: e.target.files[0] })
      const image = await this.createImage(e.target.files[0])
      this.$emit('update:questionImage', image)
    },
    async setExplanationImage (e) {
      const { valid } =
        await this.$refs.explanationImageValidateProvider.validate(e)
      if (!valid || !e.target.files[0]) return false
      this.$emit('explanationImageUpload', { file: e.target.files[0] })
      const image = await this.createImage(e.target.files[0])
      this.$emit('update:explanationImage', image)
    },
    createImage (file) {
      return new Promise(resolve => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = e => resolve(e.target.result)
      })
    },
    deleteQuestionImageUpload () {
      this.$emit('questionImageUpload', { file: null })
      this.$emit('update:questionImage', '')
    },
    deleteExplanationImageUpload () {
      this.$emit('explanationImageUpload', { file: null })
      this.$emit('update:explanationImage', '')
    },
    deleteChoice (index) {
      let choices = clonedeep(this.choices)
      if (choices[index].card_choice_is_correct) {
        choices[index - 1].card_choice_is_correct = true
      }
      choices.splice(index, 1)
      this.$emit('update:choices', choices)
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

.form.error_form {
  border: 1px solid #f6416c;
  color: #f6416c;
}

.error_text {
  margin-top: 5px;
  color: #f6416c;
}

.form.form_select {
  height: 80px;
}

.form::placeholder {
  color: #999;
}

.image {
  max-width: 100%;
  margin-top: 10px;
}

.select {
  margin-top: 40px;
}

.select + .select {
  margin-top: 10px;
}

.select_inner {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.select_inner > .group {
  margin-top: 0;
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

.radio:checked + .correct {
  background-color: #f6416c;
  pointer-events: none;
}

.delete {
  width: 40px;
  height: 40px;
  margin: 23px 0 0 30px;
  color: #999;
  font-size: 40px;
  transition: all 0.3s;

  @include mq(tb) {
    width: 35px;
    height: 35px;
    font-size: 35px;
    margin: 21px 0 0 25px;
  }

  @include mq(sp) {
    width: 30px;
    height: 30px;
    font-size: 30px;
    margin: 19px 0 0 15px;
  }
}

.delete_inner {
  cursor: pointer;
}

.delete_inner:hover {
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

.button.green:disabled {
  background-color: #fff;
  color: #999;
  border: 3px solid #999;
  pointer-events: none;
}

.button.button_image {
  margin-top: 10px;
}

.button.button_red {
  border: 3px solid #f6416c;
  color: #f6416c;
}

.button.button_add {
  margin-top: 10px;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}

.button_add_icon {
  margin-left: 10px;

  @include mq(sp) {
    margin-left: 5px;
  }
}
</style>
