<template>
  <validation-provider
    v-slot="{ errors }"
    tag="div"
    :rules="validationRule"
    :name="label"
    :vid="name"
  >
    <vue-file-agent
      ref="vueFileAgent"
      v-model="innerValue"
      @select="filesSelected($event)"
      @beforedelete="onBeforeDelete($event)"
      @delete="fileDeleted($event)"
    />
    <v-file-input v-model="innerValue" />
  </validation-provider>
</template>

<script>
  import FieldMixin from '@/components/Form/Mixins/FieldMixin'
  window.getFileRecordsInitial = function () {
    var filesBaseUrl = 'https://safrazik.com/vue-file-agent/website/assets/files/'

    var videoData = {
      name: 'Golf.mp4',
      lastModified: 1576563996233,
      sizeText: '549 KB',
      size: 561813,
      type: 'video/mp4',
      ext: 'mp4',
      dimensions: { width: 640, height: 360 },
    }
    videoData.videoThumbnail = filesBaseUrl + 'Golf-thumb.jpg'
    videoData.imageColor = [66, 62, 45]

    var fileRecords = [];
    [
      {
        name: 'sample.pdf',
        lastModified: 1565232623243,
        sizeText: '3 KB',
        size: 3028,
        type: 'application/pdf',
        ext: 'pdf',
      },
      {
        name: 'House Sparrow.jpg',
        lastModified: 1583992794341,
        sizeText: '14 KB',
        size: 14403,
        type: 'image/jpeg',
        ext: 'jpg',
      },
      { name: 'Important sheet.ods', lastModified: 1564392646095, sizeText: '31 KB', size: 31276, type: '', ext: 'ods' },
      videoData,
      {
        name: 'Test Files.zip',
        lastModified: 1572147928098,
        sizeText: '198 KB',
        size: 202680,
        type: 'application/x-zip-compressed',
        ext: 'zip',
      },
      { name: 'Document 3.docx', lastModified: 1564392646097, sizeText: '109 KB', size: 111303, type: '', ext: 'docx' },
    ].forEach(function (fd) {
      fd.url = filesBaseUrl + fd.name
      // fd.progress = 10;
      fileRecords.push(fd)
    })

    return fileRecords
  }
  export default {
    name: 'FileField',
    mixins: [FieldMixin],
    data: () => ({
      fileRecords: [],
      innerValue: null,
      uploadUrl: 'https://www.mocky.io/v2/5d4fb20b3000005c111099e3',
      uploadHeaders: { 'X-Test-Header': 'vue-file-agent' },
      fileRecordsForUpload: [],
    }),
    methods: {
      setComponentValue () {
        console.log('setted')
      },
      uploadFiles () {
        // Using the default uploader. You may use another uploader instead.
        this.$refs.vueFileAgent.upload(this.uploadUrl, this.uploadHeaders, this.fileRecordsForUpload)
        this.fileRecordsForUpload = []
      },
      deleteUploadedFile (fileRecord) {
        // Using the default uploader. You may use another uploader instead.
        this.$refs.vueFileAgent.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord)
      },
      filesSelected (fileRecordsNewlySelected) {
        console.log(fileRecordsNewlySelected)
        const validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error)

        this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords)

        console.log(this.fileRecordsForUpload)
      },
      onBeforeDelete (fileRecord) {
        const i = this.fileRecordsForUpload.indexOf(fileRecord)
        if (i !== -1) {
          this.fileRecordsForUpload.splice(i, 1)
        } else {
          if (confirm('Are you sure you want to delete?')) {
            this.$refs.vueFileAgent.deleteFileRecord(fileRecord) // will trigger 'delete' event
          }
        }
      },
      fileDeleted (fileRecord) {
        const i = this.fileRecordsForUpload.indexOf(fileRecord)
        if (i !== -1) {
          this.fileRecordsForUpload.splice(i, 1)
        } else {
          this.deleteUploadedFile(fileRecord)
        }
      },
    },
    innerValue (newVal) {
      this.$emit('input', {
        name: this.name,
        value: newVal,
      })
    },
  }
</script>
