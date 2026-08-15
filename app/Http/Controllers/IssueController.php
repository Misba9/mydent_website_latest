<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function show($slug)
    {
       $cards = [
    'overbite' => [
        'title' => 'Overbite',
        'video' => 'overbite.mp4',
        'desc' => 'An overbite occurs when the upper front teeth excessively overlap the lower front teeth when the mouth is closed. This condition is common and, if left untreated, can lead to discomfort, jaw pain, and cosmetic concerns that affect self-confidence.',
        'details' => 'Overbite is one of the most frequent types of malocclusions. It ranges in severity and can cause not only aesthetic issues but also functional difficulties like chewing problems, jaw joint pain, and speech issues. It may also lead to abnormal wear on the enamel of lower teeth, increasing the risk of tooth decay and gum issues. Early diagnosis and treatment help prevent complications and improve facial harmony.',
        'causes' => 'Several factors can contribute to the development of an overbite, including genetic predisposition, prolonged thumb-sucking or pacifier use beyond infancy, tongue thrusting during swallowing or speaking, and improper growth of the jawbones. Habits formed during childhood are often the root cause in many cases.',
        'symptoms' => 'Visible overlapping of the upper teeth over the lower teeth, jaw fatigue, facial imbalance, mouth breathing, difficulty in speaking clearly, and even sleep disturbances due to improper bite alignment.',
        'treatment' => 'Braces, clear aligners, jaw repositioning appliances, or orthognathic surgery in severe skeletal cases. Early intervention in childhood is often simpler and more effective.',
        'faqs' => [
            ['q' => 'Is overbite painful?', 'a' => 'A mild overbite usually isn’t painful, but moderate to severe overbites can lead to jaw tension, headaches, and tooth wear.'],
            ['q' => 'Can Invisalign fix overbite?', 'a' => 'Yes, Invisalign is a preferred option for treating mild to moderate cases, especially in teens and adults.'],
            ['q' => 'What causes an overbite?', 'a' => 'It may be inherited or caused by habits like thumb-sucking, tongue thrusting, or extended pacifier use during childhood.'],
            ['q' => 'Is surgery required for overbite?', 'a' => 'Only in severe jaw misalignment cases that can’t be corrected with orthodontic treatment alone.'],
            ['q' => 'How long does treatment take?', 'a' => 'Treatment time varies between 1–2 years depending on age and severity.'],
        ],
    ],

    'underbite' => [
        'title' => 'Underbite',
        'video' => 'underbite.mp4',        
        'desc' => 'An underbite is a type of malocclusion where the lower teeth project beyond the upper teeth. This condition not only affects facial appearance but also contributes to bite and speech problems.',
        'details' => 'Underbites can result in facial asymmetry, chewing difficulties, and jaw joint strain. Left untreated, the condition may cause excessive wear on teeth, chronic pain, and speech problems such as lisping or slurring. Early diagnosis is key to preventing worsening symptoms.',
        'causes' => 'Genetics are a leading cause, but jaw injuries, childhood habits like tongue thrusting, or tumors in the jawbone can also contribute.',
        'symptoms' => 'Lower jaw sticking out, biting difficulties, chronic jaw pain, uneven tooth wear, and changes in facial profile. Some may also have trouble speaking clearly or chewing food efficiently.',
        'treatment' => 'Orthodontic braces, aligners, reverse-pull face masks for kids, and surgical jaw realignment for advanced cases.',
        'faqs' => [
            ['q' => 'Is an underbite serious?', 'a' => 'It can lead to serious problems like TMJ disorders, tooth damage, and poor oral function if not corrected.'],
            ['q' => 'Can braces fix underbite?', 'a' => 'Yes, braces are very effective for mild to moderate underbite correction and may be combined with elastics.'],
            ['q' => 'What age is best to treat underbite?', 'a' => 'The earlier, the better—usually between 7 and 12 years. But adults can also benefit from treatment.'],
            ['q' => 'Is surgery always needed?', 'a' => 'Surgery is reserved for severe skeletal discrepancies where orthodontics alone is insufficient.'],
            ['q' => 'Can an underbite affect speech?', 'a' => 'Yes, it may interfere with proper tongue placement and cause articulation issues.'],
        ],
    ],

    'crossbite' => [
        'title' => 'Crossbite',
       'video' => 'crossbite.mp4',
        'desc' => 'A crossbite occurs when some of the upper teeth sit inside the lower teeth when biting down. This misalignment can affect both the front and/or back teeth and may result in dental complications.',
        'details' => 'A crossbite may lead to uneven wear of teeth, gum recession, and jaw problems like TMJ disorders. It may cause one side of the face to appear asymmetrical and can create long-term complications for dental health if not corrected early.',
        'causes' => 'Common causes include delayed loss of baby teeth, abnormal eruption of adult teeth, thumb-sucking, and hereditary jaw shape irregularities.',
        'symptoms' => 'Misaligned bite, clicking or popping of the jaw, facial asymmetry, increased risk of gum disease, and difficulty chewing.',
        'treatment' => 'Treatment may involve palatal expanders (for children), braces, Invisalign, or surgical intervention for adults with skeletal involvement.',
        'faqs' => [
            ['q' => 'What happens if I don’t fix a crossbite?', 'a' => 'Crossbites can lead to worn teeth, receding gums, and permanent jaw misalignment if untreated.'],
            ['q' => 'Is crossbite common?', 'a' => 'Yes, it’s a common condition that can be effectively treated with early orthodontic care.'],
            ['q' => 'Can adults fix crossbite?', 'a' => 'Yes, but adult cases may take longer and sometimes require surgical correction.'],
            ['q' => 'What’s the best treatment?', 'a' => 'For children, expanders and braces are ideal. Adults may need aligners or jaw surgery.'],
            ['q' => 'How long does treatment last?', 'a' => 'On average, treatment ranges from 12 months to 2 years.'],
        ],
    ],

    'open-bite' => [
        'title' => 'Open Bite',
         'video' => 'openbite.mp4',
        'desc' => 'An open bite is a type of malocclusion where the front teeth do not touch when the mouth is closed, leaving a visible gap that can affect chewing, speech, and appearance.',
        'details' => 'Open bites are often caused by habits such as thumb-sucking or tongue thrusting and can impact both dental function and aesthetics. They can lead to mouth breathing, speech impediments, and bite dysfunctions, particularly if left uncorrected.',
        'causes' => 'Commonly caused by prolonged thumb-sucking, pacifier use, tongue thrusting, or inherited jaw development patterns. Skeletal issues may also contribute.',
        'symptoms' => 'Gap between upper and lower front teeth, lisping or unclear speech, difficulty eating, and strain on back teeth.',
        'treatment' => 'Treatment may include orthodontics (braces or aligners), myofunctional therapy, or surgery in adults with skeletal causes.',
        'faqs' => [
            ['q' => 'Does open bite affect eating?', 'a' => 'Yes, it can make biting into food—like sandwiches or apples—challenging.'],
            ['q' => 'What causes open bite?', 'a' => 'Habits like thumb-sucking or tongue thrusting, or improper skeletal growth during childhood.'],
            ['q' => 'Can it be fixed without surgery?', 'a' => 'Mild to moderate cases in children can often be treated with orthodontics alone.'],
            ['q' => 'Is speech affected?', 'a' => 'Yes, open bite can cause lisps or difficulty pronouncing certain sounds.'],
            ['q' => 'How long is treatment?', 'a' => 'Most treatments last 18–24 months, depending on the method and age.'],
        ],
    ],

    'teethgaps' => [
        'title' => 'Teeth Gaps (Diastema)',
        'video' => 'spacing.mp4',
        'desc' => 'Teeth gaps, medically known as diastema, are noticeable spaces between two or more teeth. These gaps are most common between the upper front teeth but can occur anywhere in the mouth.',
        'details' => 'While some people see gaps as a unique feature, others consider them a cosmetic concern. In some cases, diastema may result in food getting trapped between teeth or uneven distribution of bite forces. This may eventually lead to gum problems or uneven enamel wear.',
        'causes' => 'Gaps may be caused by genetic factors, an oversized labial frenum (the tissue between the lip and gums), habits like thumb-sucking, or tooth size discrepancies. Gum disease and bone loss can also create gaps.',
        'symptoms' => 'Visible gaps, mild misalignment, food trapping, and occasional discomfort or speech difficulty. In some cases, no symptoms are experienced beyond aesthetics.',
        'treatment' => 'Options include braces, Invisalign, dental bonding, porcelain veneers, or gum treatment if disease is involved.',
        'faqs' => [
            ['q' => 'Are gaps harmful?', 'a' => 'While not always harmful, they can lead to food trapping and plaque accumulation if not managed.'],
            ['q' => 'Can bonding fix gaps?', 'a' => 'Yes, bonding is a fast and cost-effective solution to close small gaps.'],
            ['q' => 'Will braces close the gap?', 'a' => 'Yes, orthodontic braces or clear aligners are very effective for larger gaps or multiple diastemas.'],
            ['q' => 'What causes teeth gaps?', 'a' => 'They may result from genetics, thumb-sucking, or gum disease-related bone loss.'],
            ['q' => 'Can the gap come back?', 'a' => 'Yes, especially if retainers are not worn after orthodontic treatment.'],
        ],
    ],

    'crookedteeth' => [
        'title' => 'Crooked Teeth',
         'video' => 'crowding.mp4',
        'desc' => 'Crooked teeth refer to teeth that are misaligned, twisted, or overlapping. This condition affects both appearance and functionality, leading to potential oral health concerns.',
        'details' => 'Crooked teeth may impact self-esteem and increase the difficulty of maintaining oral hygiene. Plaque buildup and cavities become more likely, and bite issues may develop over time. Advanced cases may even contribute to TMJ problems.',
        'causes' => 'Genetics, prolonged thumb-sucking, tongue thrusting, early loss of baby teeth, or overcrowding in the jaw are typical causes. Lack of space can force permanent teeth to erupt improperly.',
        'symptoms' => 'Misaligned or overlapping teeth, jaw strain, trouble flossing, increased plaque buildup, and speech challenges in some cases.',
        'treatment' => 'Orthodontic braces, clear aligners like Invisalign, retainers for post-treatment alignment, or cosmetic contouring in mild cases.',
        'faqs' => [
            ['q' => 'Are crooked teeth genetic?', 'a' => 'Yes, they often run in families and are influenced by jaw size and shape.'],
            ['q' => 'Is it necessary to treat them?', 'a' => 'Yes, untreated misalignment can lead to tooth decay, gum disease, and jaw discomfort.'],
            ['q' => 'Can Invisalign help?', 'a' => 'Absolutely. Invisalign works well for mild to moderate cases without requiring metal braces.'],
            ['q' => 'Is it painful to fix?', 'a' => 'You may feel mild soreness initially as teeth begin to shift, but the discomfort usually subsides.'],
            ['q' => 'Can adults fix crooked teeth?', 'a' => 'Yes, modern orthodontic methods make treatment easier and more discreet for adults.'],
        ],
    ],

];


        if (!isset($cards[$slug])) {
            abort(404);
        }

        $card = $cards[$slug];

        return view('fronts.issues.show', compact('card'));
    }
}
