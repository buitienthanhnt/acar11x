import Modal from "@/Components/Modal"
import { TrashIcon, XCircleIcon } from "@heroicons/react/24/solid";
import { Button } from "@material-tailwind/react"
import { useCallback, useState } from "react";
import ImageManage from "./FileManage";

const ImageSelect = ({ value, onChange }: { value: string[], onChange: (value: string[]) => void }) => {
  const [showForm, setShowForm] = useState(false);
  const [selected, setSelected] = useState<string[]>(value || []);

  const onSelect = useCallback((path: string) => {
    /**
     * If the path is already selected, remove it from the selected array. 
     * Otherwise, add it to the selected array.
     */
    if (selected.includes(path)) {
      setSelected(selected.filter(item => item !== path));
      return;
    }
    /**
     * If the path is not already selected, add it to the selected array.
     */
    setSelected([...selected, path])
  }, [selected])

  /**
   * When the user clicks the confirm button, 
   * call the onChange function with the selected array.
   */
  const onConfirm = useCallback(() => {
    onChange(selected);
    setShowForm(false);
  }, [onChange, selected])

  return (
    <div className="flex flex-col gap-4">
      {/*** @ts-ignore*/}
      <Button variant="outlined" color="blue-gray" title="select image" className="w-full" onClick={() => {
        setShowForm(!showForm)
      }}>
        <p>Chọn hình ảnh</p>
      </Button>
      {value && <div className="flex flex-wrap gap-2">
        {value.map((image, index) => <div className="relative">
          <img key={index} src={image} alt="" className="size-24 rounded-md object-cover" />
          <TrashIcon
            className="size-6 absolute right-[6px] top-[6px] hover:text-red-500 cursor-pointer"
            onClick={() => {
              onSelect(image);
              onChange(value.filter(item => item !== image))
            }}
          />
        </div>)
        }
      </div>}
      <ImageModal
        showForm={showForm}
        setShowForm={setShowForm}
        selected={selected}
        onSelect={onSelect}
        onConFirm={onConfirm}
      ></ImageModal>
    </div>
  )
}

interface ImageModalProps {
  showForm: boolean;
  setShowForm: (value: boolean) => void;
  selected: string[];
  onSelect: (value: string) => void
  onConFirm?: () => void;
}

const ImageModal = ({ showForm, setShowForm, selected, onSelect, onConFirm }: ImageModalProps) => {


  return (
    <Modal
      show={showForm}
    >
      <div className="flex items-center justify-between p-2">
        <h2 className="text-xl font-semibold">Chọn phụ tùng:</h2>
        <XCircleIcon className="w-10 h-10" onClick={() => {
          setShowForm(false)

        }}></XCircleIcon>
      </div>
      <div className="min-h-[300px] max-h-[500px] overflow-y-auto p-2">
        <ImageManage selected={selected} onSelect={onSelect}></ImageManage>
      </div>
      <div className="p-2">
        {/*** @ts-ignore*/}
        <Button variant="outlined" color="blue-gray" title="select image" className="w-full" onClick={onConFirm}>
          <p>Confirm</p>
        </Button>
      </div>
    </Modal>
  )
}

export default ImageSelect;