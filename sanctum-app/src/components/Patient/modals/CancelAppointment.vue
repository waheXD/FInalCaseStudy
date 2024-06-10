<template>
  <div
    v-if="openClose"
    class="modal fade show"
    tabindex="-1"
    aria-hidden="true"
    id="editDoctor"
    style="display: block"
  >
    <div class="modal-dialog">
      <div class="modal-content">
       z
        <div class="modal-footer">
          <button class="btn-secondary" @click="CloseModal()">Close</button>
          <button
            type="button"
            @click="CancelAppointment()"
            class="btn btn-primary"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
      
<script>
import axios from "axios";
export default {
  name: "CancelAppointment",
  data() {
    return {
      openClose: this.visible,
      errors: null,
    };
  },
  props: {
    visible: Boolean,
  },
  methods: {
    CloseModal() {
      this.openClose = !this.openClose;
      this.$emit("update:visible", false);
      this.$emit("modal-closed");
    },
    async CancelAppointment() {
      try {
        const response = await axios.delete(
          this.$store.state.apiUrl +
            "/removeAppointment/" +
            this.$store.state.appointment.appointment_id,
          {}
        );

        if (response.status === 200) {
          this.$swal.fire({
            title: "Success!",
            text: "Successfully Canceled.",
            icon: "success",
          });
          this.openClose = !this.openClose;
          this.$emit("update:visible", false);
          this.$emit("modal-closed");
        }
      } catch (error) {
        alert(error.response.data.message);
      }
    },
  },
  watch: {
    visible: {
      handler(newVal) {
        this.openClose = newVal;
      },
    },
  },
};
</script>