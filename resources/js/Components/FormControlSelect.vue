<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from "vue";
import { useMainStore } from "@/stores/main";
import FormControlIcon from "@/components/FormControlIcon.vue";

const props = defineProps({
  name: { type: String, default: null },
  id: { type: String, default: null },
  autocomplete: { type: String, default: null },
  maxlength: { type: String, default: null },
  placeholder: { type: String, default: null },
  inputmode: { type: String, default: null },
  disabled: { type: Boolean, default: null },
  icon: { type: String, default: null },
  options: { type: Array, default: null },
  type: { type: String, default: "text" },
  modelValue: { type: [String, Number, Boolean, Array, Object], default: "" },
  valueSelect: { type: String, default: "id" },
  required: Boolean,
  borderless: Boolean,
  transparent: Boolean,
  ctrlKFocus: Boolean
});

const emit = defineEmits(["update:modelValue", "setRef"]);

const computedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit("update:modelValue", value);
  },
});

const inputElClass = computed(() => {
  const base = [
    "px-3 py-2 max-w-full focus:ring focus:outline-none border-gray-700 rounded w-full",
    "dark:placeholder-gray-400",
    computedType.value === "textarea" ? "h-24" : "h-12",
    props.borderless ? "border-0" : "border",
    props.transparent ? "bg-transparent" : "bg-white dark:bg-slate-800",
  ];

  if (props.icon) base.push("pl-10");
  return base;
});

const computedType = computed(() => (props.options ? "select" : props.type));
const controlIconH = computed(() => (props.type === "textarea" ? "h-full" : "h-12"));

const mainStore = useMainStore();
const selectEl = ref(null);
const textareaEl = ref(null);
const inputEl = ref(null);

onMounted(() => {
  if (selectEl.value) {
    emit("setRef", selectEl.value);
  } else if (textareaEl.value) {
    emit("setRef", textareaEl.value);
  } else {
    emit("setRef", inputEl.value);
  }
});

if (props.ctrlKFocus) {
  const fieldFocusHook = (e) => {
    if (e.ctrlKey && e.key === "k") {
      e.preventDefault();
      inputEl.value.focus();
    } else if (e.key === "Escape") {
      inputEl.value.blur();
    }
  };

  onMounted(() => {
    if (!mainStore.isFieldFocusRegistered) {
      window.addEventListener("keydown", fieldFocusHook);
      mainStore.isFieldFocusRegistered = true;
    }
  });

  onBeforeUnmount(() => {
    window.removeEventListener("keydown", fieldFocusHook);
    mainStore.isFieldFocusRegistered = false;
  });
}
</script>

<template>
  <div class="relative">
    <!-- SELECT -->
    <select
      v-if="computedType === 'select'"
      :id="id"
      ref="selectEl"
      v-model="computedValue"
      :name="name"
      :class="inputElClass"
      :disabled="disabled"
      :required="required"
    >
      <option :value="null" disabled selected>Seleccione una opción</option>
      <option
        v-for="option in options"
        :key="option.id ?? option.value ?? option"
        :value="option.value ?? option.id ?? option"
      >
        {{ option.text ?? option.name ?? option }}
      </option>
    </select>

    <!-- TEXTAREA -->
    <textarea
      v-else-if="computedType === 'textarea'"
      :id="id"
      ref="textareaEl"
      v-model="computedValue"
      :class="inputElClass"
      :name="name"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
    />

    <!-- INPUT -->
    <input
      v-else
      :id="id"
      ref="inputEl"
      v-model="computedValue"
      :name="name"
      :disabled="disabled"
      :maxlength="maxlength"
      :inputmode="inputmode"
      :autocomplete="autocomplete"
      :required="required"
      :placeholder="placeholder"
      :type="computedType"
      :class="inputElClass"
    />

    <FormControlIcon v-if="icon" :icon="icon" :h="controlIconH" />
  </div>
</template>
