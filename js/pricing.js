// Pricing toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const billingToggle = document.getElementById('billing-toggle');
    const pricingPrices = document.querySelectorAll('.pricing-price');
    const pricingDurations = document.querySelectorAll('.pricing-duration');
    
    if (billingToggle) {
        billingToggle.addEventListener('change', function() {
            const isAnnual = this.checked;
            
            pricingPrices.forEach(price => {
                if (isAnnual) {
                    price.textContent = price.getAttribute('data-annual').split(' ')[0] + ' ';
                    const span = document.createElement('span');
                    span.textContent = '/yr';
                    price.appendChild(span);
                } else {
                    price.textContent = price.getAttribute('data-monthly').split(' ')[0] + ' ';
                    const span = document.createElement('span');
                    span.textContent = '/mo';
                    price.appendChild(span);
                }
            });
            
            pricingDurations.forEach(duration => {
                if (isAnnual) {
                    duration.textContent = duration.getAttribute('data-annual');
                } else {
                    duration.textContent = duration.getAttribute('data-monthly');
                }
            });
        });
    }
    
    // Add FAQ section for pricing
    const faqSection = document.querySelector('.faq-section');
    if (faqSection) {
        const faqContainer = document.createElement('div');
        faqContainer.className = 'faq-container';
        
        const faqItems = [
            {
                question: "What's included in the Basic plan?",
                answer: "The Basic plan includes access to all study materials, 5 free mock tests per month, basic video lectures, and email support. It's perfect for beginners who are just starting their MEO Class 4 preparation journey."
            },
            {
                question: "Can I upgrade my plan later?",
                answer: "Yes, you can upgrade your plan at any time. When you upgrade, we'll prorate the remaining days on your current plan and apply them to your new plan."
            },
            {
                question: "Do you offer refunds?",
                answer: "We offer a 7-day money-back guarantee on all plans. If you're not satisfied with our service within the first week, you can request a full refund."
            },
            {
                question: "What payment methods do you accept?",
                answer: "We accept all major credit cards, debit cards, UPI, and net banking. All payments are processed securely through our payment gateway."
            },
            {
                question: "Is there a discount for annual billing?",
                answer: "Yes, you save 20% when you choose annual billing compared to paying monthly for 12 months."
            }
        ];
        
        faqItems.forEach((item, index) => {
            const faqItem = document.createElement('div');
            faqItem.className = 'faq-item';
            if (index === 0) faqItem.classList.add('active');
            
            const faqQuestion = document.createElement('div');
            faqQuestion.className = 'faq-question';
            faqQuestion.setAttribute('id', `pricing-faq-question-${index}`);
            faqQuestion.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
            faqQuestion.setAttribute('aria-controls', `pricing-faq-answer-${index}`);
            faqQuestion.setAttribute('tabindex', '0');
            faqQuestion.textContent = item.question;
            
            const faqAnswer = document.createElement('div');
            faqAnswer.className = 'faq-answer';
            faqAnswer.setAttribute('id', `pricing-faq-answer-${index}`);
            faqAnswer.setAttribute('aria-labelledby', `pricing-faq-question-${index}`);
            faqAnswer.setAttribute('role', 'region');
            faqAnswer.textContent = item.answer;
            
            faqItem.appendChild(faqQuestion);
            faqItem.appendChild(faqAnswer);
            faqContainer.appendChild(faqItem);
        });
        
        faqSection.appendChild(faqContainer);
    }
});
