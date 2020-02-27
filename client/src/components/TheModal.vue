<template>
  <transition name="modal" appear>
    <div :class="$style.wrap" @click.self="$emit('close')">
      <div :class="$style.window">
        <h3 :class="$style.heading">
          本当に削除しますか？
        </h3>
        <p :class="$style.desc">
          この問題集の中の問題も全て削除されます。<br>
          この操作は取り消せません。
        </p>
        <div :class="$style.buttons">
          <button :class="[$style.button, $style.red]">
            削除
          </button>
          <button :class="$style.button" @click="$emit('close')">
            キャンセル
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  mounted () {
    document.body.classList.add('prevent-scroll')
  },
  destroyed () {
    document.body.classList.remove('prevent-scroll')
  }
}
</script>

<style lang="scss" scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.4s;
}

.modal-enter,
.modal-leave-to {
  opacity: 0;
}
</style>

<style lang="scss" module>
.wrap {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(#fff, 0.6);
}

.window {
  background-color: #fff;
  border: 3px solid #00b8a9;
  border-radius: 10px;
  padding: 20px 20px 40px;
  box-shadow: 3px 3px 6px rgba(0, 0, 0, 0.15);

  @include mq(sp) {
    padding: 15px 15px 30px;
  }
}

.heading {
  font-size: 24px;
  font-weight: bold;
  text-align: center;
  line-height: 1.5;

  @include mq(sp) {
    font-size: 18px;
  }
}

.desc {
  margin-top: 10px;
  font-size: 16px;
  text-align: center;
  line-height: 1.5;

  @include mq(sp) {
    font-size: 12px;
  }
}

.buttons {
  margin-top: 30px;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;

  @include mq(sp) {
    margin-top: 30px;
  }
}

.button {
  $height-pc: 40px;
  $height-sp: 35px;

  width: 170px;
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

  @include mq(sp) {
    width: 120px;
    height: $height-sp;
    border-radius: $height-sp / 2;
    font-size: 14px;
  }
}

.button:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.button.red {
  background-color: #f6416c;
  border: 3px solid #f6416c;
  color: #fff;
}

.button + .button {
  margin-left: 20px;

  @include mq(sp) {
    margin-left: 10px;
  }
}
</style>
