<template>
  <div>
    {{activeAgents}}
    <div>
      <ul class="list-style agent-list">
        <li v-for="agent in activeAgents">{{agent}}</li>
      </ul>
      <button v-on:click="joinAllUsers" v-bind:disabled="activeAgents.length === 0">join all users</button>
    </div>
    <!--<div>
      <div v-for="connectedPeers in peerKeeper">
        <video width="320" height="240" controls="false" autoplay="true">
          <source v-bind:srcObject="testStream"/>
        </video>
      </div>
    </div>-->
  </div>
</template>

<script>
  const Peer = require('simple-peer');

  export default {
    name: 'app',
    data() {
      return {
        testStream: "",
        msg: 'Welcome to Your Vue.js App',
        socket: io("http://localhost:3000", {
          reconnect: false
        }),
        activeAgents: [],
        peerKeeper: {},
        possibleAgents: [
          "chrome",
          "firefox",
        ]
      }
    },
    mounted() {
      this.getActiveAgent();
      this.removeActiveAgent();
      this.informActiveAgent();
      //this.makePeer();
      this.listenForGeneralSockets();
    },
    methods: {
      getActiveAgent() {
        this.socket.on("getActiveAgent", (data) => {
          //alert("get active agent fired")
          this.activeAgents = data;
          let userAgent = this.getUserAgent();
          if (userAgent !== null) {
            const index = this.activeAgents.indexOf(userAgent);
            this.activeAgents.splice(index, 1);
          }
        });
      },
      removeActiveAgent() {
        this.socket.on("removeActiveAgent", (data) => {
          //alert("remove active agent fired");
          const index = this.activeAgents.indexOf(data);
          this.activeAgents.splice(index, 1);
        });
      },
      informActiveAgent() {
        let userAgent = this.getUserAgent();
        if (userAgent !== null) {
          this.socket.emit("makeActiveAgent", userAgent)
        }
      },
      getUserAgent() {
        let userAgent = navigator.userAgent;
        for (let agent of this.possibleAgents) {
          if (userAgent.toLowerCase().match(agent)) {
            if (localStorage.getItem("mode") === "incognito") {
              return "chromeincognito";
            }
            return agent;
          }
        }
        return null
      },
      makePeerForAgent(agent, initiator, offer = null) {

        let vueScope = this;

        navigator.mediaDevices.getUserMedia({audio: true, video: true}).then((stream) => {
          //alert(initiator);
          const peer = new Peer({
            initiator,
            trickle: false,
            stream: stream
          });

          //alert("agent " + agent);
          peer.agent = agent;
          this.peerKeeper[agent] = peer;

          peer.on('signal', (data) => {
            //alert(`signal ${agent}`);
            this.socket.emit("signal", {
              peer: data,
              to: agent,
              from: this.getUserAgent()
            });
          });

          if (!initiator) {
            //alert("signal be initiated")
            peer.signal(offer);
          }

          peer.on('connect', () => {
            //alert('CONNECT')
            //p.send('whatever' + Math.random())
          })

          peer.on('close', function () {
            alert(`call finished by ${this.agent}`);
            let element = document.getElementById(this.agent);
            if(element){
              element.remove();
            }
            this.destroy(); //fire when the connected peer disconnects
          });

          peer.on('error', function (err) {
            alert(`call errored by ${this.agent}`);
            let element = document.getElementById(this.agent);
            if(element){
              element.remove();
            }
            this.destroy(); //fire when the connected peer disconnects
          })


          peer.on('stream', function (stream) {
            //alert(`video is streaming for ${this.agent}`);
            let video = document.createElement('video')
            video.setAttribute("id", this.agent);
            document.body.appendChild(video)

            //video.src = window.URL.createObjectURL(stream)
            video.srcObject = stream;
            video.play()
          });

        }).catch((error) => {
          console.log("error", error);
        })
      },
      joinAllUsers() {
        for (let agent of this.activeAgents) {
          this.makePeerForAgent(agent, true);
        }
      },
      listenForGeneralSockets() {
        this.socket.on("offer", (data) => {
         // alert(`${data.from} is calling you: ${data.to}`);
          //console.log("data", data);
          this.makePeerForAgent(data.from, false, data.peer);
        });

        this.socket.on("answer", (data) => {
         // alert(`hi ${data.to} call is answered by ${data.from}`);
          this.peerKeeper[data.from].signal(data.peer);
        });

      }
    }
  }
</script>

<style>

</style>
