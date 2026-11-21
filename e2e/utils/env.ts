function required(name: string, fallback?: string): string {
  const value = process.env[name] ?? fallback;
  if (!value) {
    throw new Error(`Missing env ${name}. Copy e2e/.env.example to e2e/.env`);
  }
  return value;
}

export const env = {
  baseURL: process.env.BASE_URL || 'http://127.0.0.1:8000',
  admin: {
    email: required('ADMIN_EMAIL', 'admin@mydent.in'),
    password: required('ADMIN_PASSWORD', '123456'),
  },
  doctor: {
    email: required('DOCTOR_EMAIL', 'doctor@mydent.in'),
    password: required('DOCTOR_PASSWORD', '123456'),
  },
  patient: {
    email: required('PATIENT_EMAIL', 'patient@mydent.in'),
    password: required('PATIENT_PASSWORD', '123456'),
  },
  razorpayConfigured: Boolean(process.env.RAZORPAY_KEY && process.env.RAZORPAY_SECRET),
};
