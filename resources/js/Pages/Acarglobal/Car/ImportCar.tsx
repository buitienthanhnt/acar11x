import { Head, router, useForm, usePage, usePoll } from "@inertiajs/react"
import ContentLayout from "../Layout/ContentLayout"
import { FormField } from "../types/FormField"
import TextInputField from "../components/form/TextInputField"
import { useCallback, useRef, } from "react"
import debounce from 'lodash/debounce';
import { Car } from "../types/CarType";
import PrimaryButton from "@/Components/PrimaryButton"
import { XMarkIcon } from "@heroicons/react/24/solid"
import { CustomerType } from "../types/CustomerType"

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
   * data: {
   *   key: string,
   *  suspension: string,
   *  type: string,
   *  vin: string,
   *  customer: string,
   *  phone: string,
   *  address: string,
   *  km: number,
   * }
   */
  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm({
    // key: '',
    // suspension: '',
    // type: '',
    // vin: '',
    customer: null,
    phone: '',
    address: '',
    // km: 0,
    ...selected,
  });

  const onSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    post('/acar/import-car',);
  }


  const inputRef = useRef<HTMLInputElement>(null);
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
        <form className="px-2 py-8 grid grid-cols-2 space-x-2 justify-center relative bg-gray-200 shadow-md overflow-hidden sm:rounded-lg "
          onSubmit={onSubmit}
          autoComplete="true">
          <XMarkIcon className='size-9 absolute left-2 hover:rotate-12 hover:text-orange-800' onClick={() => {
            setData({
              key: '',
              suspension: '',
              type: '',
              vin: '',
              customer: null,
              phone: '',
              address: '',
              km: 0,
            });

          }}></XMarkIcon>
          <div className="flex-1 space-y-2 sm:max-w-md md:max-w-2xl">
            <div >
              <div className="flex space-x-2 w-full items-center justify-between ">
                <span className="font-semibold text-info text-md w-1/5 flex-1 after:content-['*'] after:ml-0.5 after:text-red-500">Biển số </span>
                <input
                  required
                  type={"text"}
                  placeholder="key"
                  value={data.key}
                  className={
                    'flex rounded-md bg-transparent border-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-3/5' +
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
              list="suspensionx"
              value={data.suspension}
              label={'Hãng xe(vd: Audi)'}
              onChange={(e: { target: { value: any } }) => setData('suspension', e.target.value)}
              placeholder={'suspension'}
              name={'suspension'}
            />
            <datalist id="suspensionx">
              <option value="BMW" />
              <option value="Audi" />
              <option value="Mercedes" />
              <option value="Honda" />
              <option value="Toyota" />
              <option value="Nissan" />
              <option value="Mazda" />
              <option value="Subaru" />
              <option value="Mitsubishi" />
              <option value="Kia" />
              <option value="Hyundai" />
              <option value="Suzuki" />
              <option value="Isuzu" />
              <option value="Volkswagen" />
              <option value="Ford" />
              <option value="Chevrolet" />
              <option value="Vinfast" />
              <option value="Bentley" />
              <option value="Porsche" />
              <option value="Ferrari" />
              <option value="Lamborghini" />
              <option value="Bugatti" />
              <option value="Rolls-Royce" />
              <option value="Maserati" />
              <option value="Lexus" />
              <option value="Tesla" />
              <option value="Jaguar" />
              <option value="Land Rover" />
              <option value="Aston Martin" />
            </datalist>

            <TextInputField
              value={data.type}
              name={'type'}
              label={'Loại xe(vd: A4)'}
              onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('type', e.target.value)}
              placeholder={'type'}
            />

            <TextInputField
              value={data.vin}
              label={'Mã VIN'}
              name="vin"
              onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('vin', e.target.value)}
              placeholder={'vin'}
            />

            <TextInputField
              value={data.km}
              label={'Số km hiện tại'}
              name="km"
              onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('km', parseFloat(e.target.value) || 0)}
              placeholder={'km'}
              type={'number'}
              min={0}
            />

            <div>
              <PrimaryButton className="w-full content-center text-center justify-center mt-6"
                disabled={processing || !data.key}>
                Tiếp tục
              </PrimaryButton>
            </div>
          </div>
          <CustomerForm data={data} setData={setData} />
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

const CustomerForm = ({ data, setData }: {
  data: { [key: string]: any; };
  setData: (key: string, value: any) => void;
}) => {
  const { customers } = usePage().props as any;

  /**
  * goi api lay danh sach xe vao sau 250ms moi qua trinh nhap gia tri tim kiem
  * @param value
  */
  const searchCustomer = useCallback(
    debounce((value) => {
      if (!value || value.length > 2) {
        router.get('/acar/xe-vao', { customer: value }, {
          preserveState: true,
          replace: true
        });
      }
    }, 250),
    []
  );

  return (
    <div className="flex flex-col flex-1 space-y-2 items-end justify-end">
      <div className="relative flex w-full">
        <TextInputField
          value={data.customer?.name}
          label={'Khách hàng'}
          name="customer.name"
          required
          // list="customer-list"
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => {
            setData('customer.name', e.target.value);
            searchCustomer(e.target.value);
          }}
          placeholder={'name'}
        ></TextInputField>
        <div className={`absolute z-10 right-0 top-12 rounded-md bg-white shadow-md ${data.customer?.name && data.customer?.phone ? 'hidden' : ''}`}>
          {customers.map((customer: CustomerType) => (
            <div
              key={customer.id}
              className="p-2 hover:bg-gray-200 cursor-pointer text-left" // Hoạt động 100% với CSS
              onClick={() => {
                setData('customer', customer);
              }}
            >
              {customer.name} {customer.phone}
            </div>
          ))}
        </div>
      </div>
      <TextInputField
        value={data.customer?.phone}
        label={'SĐT'}
        name='customer.phone'
        required
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('customer.phone', e.target.value)}
        placeholder={'phone'}
      />

      <TextInputField
        value={data.customer?.address}
        label={'Địa chỉ'}
        name='customer.address'
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('customer.address', e.target.value)}
        placeholder={'address'}
      />
      <TextInputField
        value={data.customer?.company}
        label={'Tên công ty'}
        name='customer.company'
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('customer.company', e.target.value)}
        placeholder={'company'}
      />
    </div>
  )
}

ImportCar.layout = (page: any) => <ContentLayout>{page}</ContentLayout>

export default ImportCar
