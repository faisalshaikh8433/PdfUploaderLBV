<template>
	<div class="w-25 border bg-grey">
		<div class="d-flex align-items-center justify-content-between p-3">
			<span class="fw-bold" style="font-size: 13px">FILES</span>
			<span
				class="fw-lighter"
				data-bs-toggle="modal"
				data-bs-target="#uploadModal"
				style="color: #9ca0b2; font-size: 13px"
			>
				UPLOAD
				<i class="fa fa-upload"></i>
			</span>
		</div>
		<div
			class="m-2 p-2 documents d-flex"
			v-for="(pdf, i) in pdfs"
			:data-filepath="pdf.filepath"
			:data-filename="pdf.filename + ' #' + (i + 1)"
			@click="displayPdfContent($event)"
		>
			<div class="bar">&nbsp;</div>
			<div style="margin-left: 10px">
				<P class="fw-bold" style="font-size: 14px"
					>{{ pdf.filename }} #{{ i + 1 }}</P
				>
				<P style="font-size: 12px">Me, Dustin</P>
			</div>
		</div>
		<div
			class="modal fade"
			id="uploadModal"
			tabindex="-1"
			aria-labelledby="uploadModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Select a pdf file</h5>
						<button
							type="button"
							class="btn-close"
							data-bs-dismiss="modal"
							aria-label="Close"
						></button>
					</div>
					<div class="modal-body">
						<div
							class="
								alert alert-warning alert-dismissible
								fade
								show
							"
							role="alert"
							v-if="errorMessageNotEmpty"
						>
							{{ errorMessage }}
							<button
								type="button"
								class="btn-close"
								data-bs-dismiss="alert"
								aria-label="Close"
							></button>
						</div>
						<div class="mt-2">
							<label class="label-control"> File Name </label>
							<input
								type="text"
								class="form-control"
								v-model="filename"
							/>
						</div>
						<div class="mt-2">
							<label class="label-control"> File </label>
							<input
								type="file"
								class="form-control"
								@change="handleFileUpload($event)"
							/>
						</div>
					</div>
					<div class="modal-footer">
						<button
							type="button"
							class="btn btn-secondary"
							data-bs-dismiss="modal"
						>
							Close
						</button>
						<button
							class="btn btn-primary"
							type="button"
							disabled
							v-if="uploading"
						>
							<span
								class="spinner-grow spinner-grow-sm"
								role="status"
								aria-hidden="true"
							></span>
							Uploading...
						</button>
						<button
							type="button"
							class="btn btn-primary"
							@click="submitFile"
							:disabled="fileNotSelected"
							v-else
						>
							Upload
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { Modal } from "bootstrap";
export default {
	props: {
		pdfs: {
			type: Array,
			default: [],
		},
	},
	data() {
		return {
			pdf: "",
			filename: "",
			uploading: false,
			errorMessage: "",
		};
	},
	methods: {
		submitFile() {
			let formData = new FormData();
			formData.append("filename", this.filename);
			formData.append("pdf", this.pdf);
			this.uploading = true;
			this.errorMessage = "";
			axios
				.post("/upload", formData, {
					headers: {
						"Content-Type": "multipart/form-data",
					},
				})
				.then(() => {
					this.uploading = false;
					this.filename = "";
					this.pdf = "";
					this.$emit("newPdfAdded");
					let updloadModal = Modal.getInstance(
						document.getElementById("uploadModal")
					);
					updloadModal.hide();
				})
				.catch((error) => {
					this.uploading = false;
					this.errorMessage = error.response.data.errors.pdf[0];
				});
		},
		handleFileUpload(event) {
			this.pdf = event.target.files[0];
		},
		displayPdfContent(event) {
			Array.from(document.querySelectorAll(".documents")).forEach((el) =>
				el.classList.remove("active")
			);
			event.currentTarget.classList.add("active");
			let filepath = event.currentTarget.getAttribute("data-filepath");
			let filename = event.currentTarget.getAttribute("data-filename");
			this.$emit("pdfUrl", { filepath, filename });
		},
	},
	computed: {
		fileNotSelected() {
			return this.pdf == "";
		},
		errorMessageNotEmpty() {
			return this.errorMessage !== "";
		},
	},
};
</script>

<style lang="scss" scoped>
.bg-grey {
	background-color: #f3f3f3;
}
.documents:hover {
	background-color: #ece9e9;
}
.documents {
	color: #394166;
}

.active {
	background-color: #ffffff;
}
.active .bar {
	background-color: #4077e4;
}
</style>