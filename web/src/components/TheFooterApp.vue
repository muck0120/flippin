<template>
  <div :class="$style.wrap">
    <small :class="$style.copyright">
      <fa :icon="faCopyright" :class="$style.copyright__icon" />
      <span :class="$style.copyright__text">2020 Flippin</span>
    </small>
    <NLink to="https://twitter.com/flippin" :class="$style.contact">
      <span :class="$style.contact__text">Contact Us</span>
      <fa :icon="faExternalLinkAlt" :class="$style.copyright__icon" />
    </NLink>
  </div>
</template>

<script>
import { faCopyright, faExternalLinkAlt } from '@fortawesome/free-solid-svg-icons'

export default {
  computed: {
    faCopyright () {
      return faCopyright
    },
    faExternalLinkAlt () {
      return faExternalLinkAlt
    }
  }
}
</script>

<style lang="scss" module>
.wrap {
  width: 100%;
  height: 100%;
  background-color: #00b8a9;
  color: #fff;
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

.copyright {
  display: flex;
  justify-content: flex-start;
  align-items: center;
}

.copyright__text {
  margin-left: 5px;
}

.contact {
  transition: all 0.3s;
}

.contact:hover {
  opacity: 0.7;
  transition: all 0.3s;
}

.copyright__icon {
  margin-left: 5px;
}
</style>
