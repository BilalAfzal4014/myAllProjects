<template>
  <button class="btn-fav-box">
    <span class="fav">
      <svg fill="none" height="18" viewBox="0 0 20 18" width="20"
           xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M19.5425 5.99771C19.5425 9.07905 17.1199 11.0489 14.9151 13.308C12.4837 15.7929 11.0371 17.8261 10.2092 17.6725V17.6815C9.3813 17.8351 7.93468 15.802 5.50331 13.317C3.29851 11.058 0.875854 9.08809 0.875854 6.00675C0.876654 4.86668 1.2388 3.75848 1.90653 2.85273C2.57427 1.94699 3.5106 1.29391 4.57137 0.994029C5.63215 0.694143 6.75858 0.764074 7.77727 1.19306C8.79596 1.62204 9.65044 2.38629 10.2092 3.36818C10.7658 2.38407 11.6193 1.61727 12.6379 1.18612C13.6566 0.754965 14.7837 0.683436 15.8455 0.982569C16.9072 1.2817 17.8445 1.93487 18.5127 2.84125C19.1809 3.74764 19.5428 4.85686 19.5425 5.99771Z"
          fill="#CAA8F5"
        />
      </svg>
    </span>
  </button>
</template>

<script>
export default {
    name: "FavoriteIcon"
};
</script>

<style scoped>
#descriptive-popup .btn-fav-box {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  transition: all .3s ease-in-out;
  -webkit-transition: all .3s ease-in-out;
  -moz-transition: all .3s ease-in-out;
  -ms-transition: all .3s ease-in-out;
  -o-transition: all .3s ease-in-out;
}
#descriptive-popup .btn-fav-box .fav {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border: 1px solid #690FAD;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  -webkit-box-shadow: 0 4.88889px 4.88889px rgba(0, 0, 0, 0.25);
  box-shadow: 0 4.88889px 4.88889px rgba(0, 0, 0, 0.25);
}
#descriptive-popup .btn-fav-box .fav svg,
#descriptive-popup .btn-fav-box .fav path {
  fill: rgba(105, 15, 173, 0.65);
}
#descriptive-popup .btn-fav-box .fav:hover {
  background: #690FAD;
}
#descriptive-popup .btn-fav-box .fav:hover svg,
#descriptive-popup .btn-fav-box .fav:hover path {
  fill: #CAA8F5;
}
#descriptive-popup .btn-fav-box.active .fav {
  background: #690FAD;
}
#descriptive-popup .btn-fav-box.active .fav svg,
#descriptive-popup .btn-fav-box.active .fav path {
  fill: #CAA8F5;
}
</style>