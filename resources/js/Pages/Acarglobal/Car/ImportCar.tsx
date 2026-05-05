import { Head, router, useForm, usePoll } from "@inertiajs/react"
import ContentLayout from "../Layout/ContentLayout"
import FormRender from "../components/form/FormRender"
import { FormField } from "../types/FormField"
import TextInputField from "../components/form/TextInputField"
import { useCallback, useRef, } from "react"
import debounce from 'lodash/debounce';
import { Car } from "../types/CarType";
import PrimaryButton from "@/Components/PrimaryButton"
import { XMarkIcon } from "@heroicons/react/24/solid"

type Props = {
  form_fields: FormField[];
  cars: Car[];
  selected: Car;
}

const ImportCar = ({ form_fields, cars, selected }: Props) => {
  /**
    * Convert form_fields to object
    * get form fields
    */
  let _form_fields: { [key: string]: string | any } = {};
  form_fields.forEach((field) => {
    _form_fields[field.name] = field.value;
  })
  /**
   * init form data
   */
  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm(_form_fields);

  const onSubmit = (e) => {
    e.preventDefault();
    post('/acar/import-car',);
  }


  const inputRef = useRef(null);
  /**
   * goi api lay danh sach xe vao sau 250ms moi qua trinh nhap gia tri tim kiem
   * @param value
   */
  const handleSearch = useCallback(
    debounce((value) => {
      if (!value || value.length > 2) {
        router.get('/acar/xe-vao', { search: value }, {
          preserveState: true,
          replace: true
        });
      }
    }, 250),
    []
  );

  const onSelectCar = (car: Car) => {
    // console.log(car, inputRef.current);

    if (inputRef.current) {
      inputRef.current.value = car.key
    }
  }

  return (
    <>
      <Head title="xe vào"></Head>
      <div className="container mx-auto p-2">
        <form className="px-2 py-8 grid grid-cols-2 space-x-2 justify-center relative bg-gray-200 shadow-md overflow-hidden sm:rounded-lg " onSubmit={onSubmit} autoComplete="true">
          <XMarkIcon className='size-9 absolute left-2 hover:rotate-12 hover:text-orange-800' onClick={() => {
            setData({
              key: '',
              suspension: '',
              type: '',
              vin: '',
              customer: '',
              phone: '',
              address: '',
              km: 0,
            });

          }}></XMarkIcon>
          <div className="flex-1 space-y-2 sm:max-w-md md:max-w-2xl">
            <div >
              <div className="flex space-x-2 w-full items-center justify-between ">
                <span className="font-semibold text-info text-md w-1/5 flex-1">Biển số: </span>
                <input required type={"text"} placeholder="key" value={data.key} className={
                  'flex rounded-md bg-transparent border-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-3/5 ' +
                  (processing ? ' bg-gray-100 cursor-not-allowed' : '')
                }
                  onChange={(e) => {
                    setData('key', e.target.value);
                    handleSearch(e.target.value);
                  }}
                />

              </div>
              <div className="flex absolute left-1/2 top-5 justify-end">
                {
                  !!cars.length && <div className="border p-2 border-black mt-2 rounded-xl w-fit">
                    {cars.map((car: any, index) => <CarItem key={index} car={car} onCheck={onSelectCar}></CarItem>)}
                  </div>
                }
              </div>
            </div>

            <TextInputField
              value={data.suspension}
              label={'Hãng xe(vd: Audi)'}
              onChange={(e) => setData('suspension', e.target.value)}
              placeholder={'suspension'}
            />

            <TextInputField
              value={data.type}
              label={'Loại xe(vd: A4)'}
              onChange={(e) => setData('type', e.target.value)}
              placeholder={'type'}
            />

            <TextInputField
              value={data.vin}
              label={'Mã VIN'}
              onChange={(e) => setData('vin', e.target.value)}
              placeholder={'vin'}
            />

            <TextInputField
              value={data.km}
              label={'Số km hiện tại'}
              onChange={(e) => setData('km', e.target.value)}
              placeholder={'km'}
              type={'number'}
              min={0}
            />

            <div>
              <PrimaryButton className="w-full content-center text-center justify-center mt-6" disabled={processing || !data.key}>
                Tiếp tục
              </PrimaryButton>
            </div>
          </div>
          <div className="flex flex-col flex-1 space-y-2 items-end justify-end">
            <TextInputField
              value={data.customer}
              label={'Khách hàng'}
              onChange={(e) => setData('customer', e.target.value)}
              placeholder={'customer'}
            />

            <TextInputField
              value={data.phone}
              label={'SĐT'}
              onChange={(e) => setData('phone', e.target.value)}
              placeholder={'phone'}
            />

            <TextInputField
              value={data.address}
              label={'Địa chỉ'}
              onChange={(e) => setData('address', e.target.value)}
              placeholder={'address'}
            />
          </div>
        </form>
      </div>
    </>
  )
}

const CarItem = ({ car: { key, suspension, type, ...rest }, onCheck }: { car: Car, onCheck: (car: Car) => void }) => {

  const onSelectCar = useCallback(() => {
    onCheck({ key, suspension, type, ...rest });

    router.get('/acar/xe-vao', { selected: key, }, {
      preserveState: false,
      replace: true
    });
  }, [key, onCheck, rest, suspension, type])

  return (
    <div className="w-fit p-2 hover:text-blue-600" onClick={onSelectCar}>
      {key} - {suspension} - {type}
    </div>
  );

}

ImportCar.layout = (page: any) => <ContentLayout>{page}</ContentLayout>

export default ImportCar
