import { Listbox } from "@headlessui/react"
import { FunctionComponent, useCallback, useMemo } from "react"
import { ChevronUpDownIcon } from '@heroicons/react/16/solid'
import { CheckIcon } from '@heroicons/react/20/solid'
import { SelectOptionType } from "../../types/FormFieldType";

type Props = {
  name: string;
  label?: string;
  options: SelectOptionType[];
  value: string | number;
  onChange?: (name: string, value: any) => void;
};

const SelectOption: FunctionComponent<Props> = (props) => {
  const { name, label, options, onChange } = props;

  const selected = useMemo(() => {
    return options.find((item) => item.value == props.value);
  }, [props.value])

  /**
   * onselect setSelected value and call into onchange function.
   */
  const onSelect = useCallback((value: SelectOptionType) => {
    onChange?.(name, value.value);
  }, [onChange])

  if (!options) {
    return null;
  }

  return (
    <div className="flex-1 bg-gray-400 rounded-md p-1">
      <Listbox value={selected} onChange={onSelect}>
        <Listbox.Label className="block text-sm/6 font-semibold text-gray-900 pl-3 uppercase">{label}</Listbox.Label>
        <div className="relative mt-2">
          <Listbox.Button className="grid w-full cursor-default grid-cols-1 rounded-md bg-white py-1.5 pr-2 pl-3 text-left text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-indigo-600 sm:text-sm/6">
            <span className="col-start-1 row-start-1 flex items-center gap-3 pr-6">
              {selected?.image && <img alt="" src={selected.image} className="size-5 shrink-0 rounded-full bg-gray-100" />}
              {selected?.label && <span className="block truncate">{selected.label}</span>}
            </span>
            <ChevronUpDownIcon
              aria-hidden="true"
              className="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-500 sm:size-4"
            />
          </Listbox.Button>

          <Listbox.Options
            className="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg outline-1 outline-black/5 options-leave:transition options-leave:duration-100 options-leave:ease-in options-closed:options-leave:opacity-0 sm:text-sm"
          >
            {options.map((item) => (
              <Listbox.Option
                key={item.value}
                value={item}
                className={`group rounded-md relative cursor-default py-2 pr-9 pl-3 text-gray-900 select-none options-focus:bg-indigo-600 options-focus:text-white options-focus:outline-hidden ${selected?.value === item.value ? 'bg-gray-200' : ''}`}
              >
                <div className="flex items-center">
                  {item?.image && <img alt="" src={item.image} className="size-5 shrink-0 rounded-full" />}
                  <span className="ml-3 block truncate font-normal group-options-selected:font-semibold">{item.label}</span>
                </div>

                {(selected?.value === item.value) && <span className="absolute inset-y-0 right-0 flex items-center pr-4 text-indigo-600 group-not-options-selected:hidden group-options-focus:text-white">
                  <CheckIcon aria-hidden="true" className="size-5" />
                </span>}
              </Listbox.Option>
            ))}
          </Listbox.Options>
        </div>
      </Listbox>
    </div>
  )
}

export { SelectOption };