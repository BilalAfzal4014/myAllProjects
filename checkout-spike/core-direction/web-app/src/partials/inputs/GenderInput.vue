<template>
  <div class="nationality group">
    <label for="gender">Gender</label>
    <select id="gender" name="gender" @change="changeGender">
      <option value="">
        Select Gender
      </option>
      <option value="m" :selected="gender == 'm'">
        Male
      </option>
      <option value="f" :selected="gender == 'f'">
        Female
      </option>
    </select>
    <svg width="15" height="10" viewBox="0 0 15 10" fill="none">
      xmlns="http://www.w3.org/2000/svg">
      <path
        d="M14.8843 1.7895C14.891 2.05545 14.7906 2.27108 14.6164 2.45077C12.4464 4.77959 10.283 7.1084 8.11295 9.43003C7.6776 9.89723 7.21546 9.89004 6.77341 9.42284C4.61006 7.1084 2.4534 4.79396 0.296738 2.47233C-0.0850307 2.06263 -0.0984261 1.53074 0.256552 1.09948C0.430692 0.891039 0.631623 0.696972 0.839252 0.517279C1.14735 0.258522 1.5693 0.287273 1.8774 0.560406C1.94437 0.617907 2.00465 0.689783 2.06493 0.754473C3.75275 2.55859 5.44057 4.35551 7.13509 6.15962C7.44988 6.49745 7.44988 6.49745 7.77807 6.15244C9.4324 4.37707 11.0867 2.60171 12.7411 0.833537C12.8683 0.704159 13.0023 0.581969 13.1496 0.474153C13.2769 0.380713 13.4309 0.337586 13.5917 0.337586C14.1007 0.351961 14.891 1.22167 14.8843 1.7895Z"
        fill="white"
      />
    </svg>
  </div>
</template>

<script>
export default {
    name: "GenderInput",
    props:{
        gender: {
            type: String,
            default: "",
        },
    },
    methods:{
        changeGender(event){
            this.$emit("changed",event.target.value);
        }
    }
};
</script>

<style scoped>
select {
  appearance: none;
  width: 100%;
  border-bottom: 1px solid black;
  position: relative;
  top: -10px;
  padding-left: 10px;
  
}
.nationality.group label {
  position: relative;
  top: -10px;
}
.nationality.group svg {
  position: absolute;
  right: 7px;
  top: 20px;
}
</style>