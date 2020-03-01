import { extend } from 'vee-validate'
import { required, email, max } from 'vee-validate/dist/rules'

extend('required', {
  ...required,
  message: '必須項目です。'
})

extend('max', {
  ...max,
  message: '{length}文字以下で入力して下さい。'
})

extend('email', {
  ...email,
  message: '正しいメール形式で入力して下さい。'
})
