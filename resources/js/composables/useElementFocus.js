import { nextTick } from 'vue';

export function useElementFocus() {
    /**
     * Focus a given element ref after the DOM updates.
     * @param {import('vue').Ref<HTMLElement | null>} elementRef
     */
    function focus(elementRef) {
        if (!elementRef || !elementRef.value) return;

        nextTick(() => {
            elementRef.value.focus();
        });
    }

    /**
     * Focus and select the content of an input/textarea element ref after DOM updates.
     * @param {import('vue').Ref<HTMLElement | null>} elementRef
     */
    function focusAndSelect(elementRef) {
        if (!elementRef || !elementRef.value) return;

        nextTick(() => {
            elementRef.value.select();
        });
    }

    return { focus, focusAndSelect };
}
