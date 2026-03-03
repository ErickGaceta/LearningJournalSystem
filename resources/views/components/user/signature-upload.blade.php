<flux:modal name="signature-upload" class="max-w-lg">
    <div class="space-y-6" x-data="signatureUploader()">

        <div>
            <flux:heading size="lg">Upload Signature</flux:heading>
            <flux:subheading>Upload, crop, and remove background automatically.</flux:subheading>
        </div>

        {{-- Step 1: Drop Zone --}}
        <div x-show="step === 'upload'">
            <div
                class="flex flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 p-10 text-center transition hover:border-zinc-400 cursor-pointer"
                x-bind:class="dragging ? 'border-blue-500 bg-blue-50 dark:bg-blue-950' : ''"
                x-on:dragover.prevent="dragging = true"
                x-on:dragleave.prevent="dragging = false"
                x-on:drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                x-on:click="$refs.fileInput.click()">
                @if(Auth::user()->signature)
                <img src="{{ asset(Auth::user()->signature->signature_path) }}"
                    class="max-h-24 object-contain rounded opacity-50 mb-2" />
                <p class="text-xs text-zinc-400">Current signature — click to replace</p>
                @else
                <flux:icon.cloud-arrow-up class="size-10 text-zinc-400" />
                <div class="text-sm">
                    <span class="font-medium text-zinc-700 dark:text-zinc-300">Click to upload</span>
                    or drag and drop
                </div>
                @endif

                <input type="file" accept="image/jpeg,image/png,image/webp"
                    class="hidden" x-ref="fileInput"
                    x-on:change="handleFile($event.target.files[0])" />
            </div>
        </div>

        {{-- Step 2: Crop --}}
        <div x-show="step === 'crop'" class="space-y-4">
            <p class="text-sm text-zinc-500">Crop your signature, then click <strong>Apply</strong>.</p>
            <div class="rounded-xl overflow-hidden bg-zinc-100 dark:bg-zinc-800 max-h-64">
                <img x-ref="cropImage" class="max-w-full" />
            </div>
            <div class="flex justify-end gap-2">
                <flux:button variant="ghost" x-on:click="resetUploader()">Back</flux:button>
                <flux:button variant="primary" x-on:click="applyCrop()">Apply Crop</flux:button>
            </div>
        </div>

        {{-- Step 3: Preview + Submit --}}
        <div x-show="step === 'preview'" class="space-y-4">
            <p class="text-sm text-zinc-500">Background removed. Looking good?</p>
            <div class="flex items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-700 p-6 bg-[url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEwAACxMBAJqcGAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ4GraHQAAABNSURBVDiNY2RgYPgPRGMCjHiKYGJgYGBgYGT4z8BAT4+RIQIAAMjMwMDA8J+RkRExDAAAACQHwBDIABkCsABGEkYAAAAASUVORK5CYII=')]">
                <img x-bind:src="finalPreview" class="max-h-32 object-contain" />
            </div>

            <form action="{{ route('user.signature.save') }}" method="POST" x-ref="uploadForm">
                @csrf
                <input type="hidden" name="signature_base64" x-bind:value="finalPreview" />
                @error('signature_base64')
                <flux:callout variant="danger" icon="x-circle" class="mt-3">{{ $message }}</flux:callout>
                @enderror
                <div class="flex justify-end gap-2 mt-4">
                    <flux:button variant="ghost" x-on:click="step = 'crop'; initCropper()">Re-crop</flux:button>
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="primary" icon="arrow-up-tray">
                        Upload
                    </flux:button>
                </div>
            </form>
        </div>

    </div>
</flux:modal>

{{-- Cropper.js --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
    function signatureUploader() {
        return {
            step: 'upload',
            dragging: false,
            rawDataUrl: null,
            finalPreview: null,
            cropper: null,

            handleFile(file) {
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    this.rawDataUrl = e.target.result;
                    this.step = 'crop';
                    this.$nextTick(() => this.initCropper());
                };
                reader.readAsDataURL(file);
            },

            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                const img = this.$refs.cropImage;
                img.src = this.rawDataUrl;
                this.cropper = new Cropper(img, {
                    viewMode: 1,
                    autoCropArea: 0.9,
                    movable: true,
                    zoomable: true,
                    background: false,
                });
            },

            applyCrop() {
                const canvas = this.cropper.getCroppedCanvas({
                    maxWidth: 800,
                    maxHeight: 400
                });
                this.finalPreview = this.removeBg(canvas);
                this.step = 'preview';
            },

            removeBg(canvas) {
                const ctx = canvas.getContext('2d');
                const {
                    width,
                    height
                } = canvas;
                const data = ctx.getImageData(0, 0, width, height);
                const px = data.data;

                for (let i = 0; i < px.length; i += 4) {
                    const r = px[i],
                        g = px[i + 1],
                        b = px[i + 2];
                    // Make light pixels transparent (threshold: 200)
                    const brightness = (r + g + b) / 3;
                    if (brightness > 200) {
                        px[i + 3] = 0; // fully transparent
                    } else {
                        // Darken remaining pixels for clean signature look
                        px[i] = Math.max(0, r - 20);
                        px[i + 1] = Math.max(0, g - 20);
                        px[i + 2] = Math.max(0, b - 20);
                    }
                }

                ctx.putImageData(data, 0, 0);
                return canvas.toDataURL('image/png');
            },

            resetUploader() {
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
                this.step = 'upload';
                this.rawDataUrl = null;
                this.finalPreview = null;
            }
        }
    }
</script>